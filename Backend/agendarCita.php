<?php
require_once 'confBD.php';
header('Content-Type: application/json');

$conexion = Conectarse();

// Permitir acción desde JSON (para fetch con application/json)
$accion = $_GET['accion'] ?? $_POST['accion'] ?? null;
if (!$accion) {
    $input = json_decode(file_get_contents('php://input'), true);
    $accion = $input['accion'] ?? null;
}

// Obtener sucursales
if ($accion === 'get_sucursales') {
    $result = $conexion->query("SELECT ID, Nombre FROM sucursal");
    $sucursales = [];
    while ($row = $result->fetch_assoc()) {
        $sucursales[] = $row;
    }
    echo json_encode(['success' => true, 'data' => $sucursales]);
    $conexion->close();
    exit;
}

if ($accion === 'get_especialidades_sucursal') {
    $sucursal = $_GET['sucursal'] ?? '';
    if (!$sucursal) {
        echo json_encode(['success' => false, 'data' => []]);
        $conexion->close();
        exit;
    }
    $sql = "
        SELECT DISTINCT e.ID, e.Nombre
        FROM especialidad e
        INNER JOIN doctor_especialidades de ON de.Especialidad = e.ID
        INNER JOIN persona p ON p.ID = de.Doctor_ID
        INNER JOIN usuario u ON u.Persona = p.ID AND u.Rol = 2
        INNER JOIN consultorio_horario ch ON ch.Doctor_ID = p.ID
        INNER JOIN consultorio c ON c.ID = ch.Consultorio_ID
        WHERE c.Sucursal = ?
    ";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $sucursal);
    $stmt->execute();
    $result = $stmt->get_result();
    $especialidades = [];
    while ($row = $result->fetch_assoc()) {
        $especialidades[] = $row;
    }
    $stmt->close();
    echo json_encode(['success' => true, 'data' => $especialidades]);
    $conexion->close();
    exit;
}

if ($accion === 'get_doctores_sucursal_especialidad') {
    $sucursal = $_GET['sucursal'] ?? '';
    $especialidad = $_GET['especialidad'] ?? '';
    if (!$sucursal || !$especialidad) {
        echo json_encode(['success' => false, 'data' => []]);
        $conexion->close();
        exit;
    }
    $sql = "
        SELECT DISTINCT p.ID, CONCAT(p.Nombre, ' ', p.Ape_Pa, ' ', p.Ape_Ma) AS nombre
        FROM persona p
        INNER JOIN doctor_especialidades de ON de.Doctor_ID = p.ID
        INNER JOIN especialidad e ON e.ID = de.Especialidad
        INNER JOIN usuario u ON u.Persona = p.ID AND u.Rol = 2
        INNER JOIN consultorio_horario ch ON ch.Doctor_ID = p.ID
        INNER JOIN consultorio c ON c.ID = ch.Consultorio_ID
        WHERE c.Sucursal = ? AND e.ID = ?
    ";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ii", $sucursal, $especialidad);
    $stmt->execute();
    $result = $stmt->get_result();
    $doctores = [];
    while ($row = $result->fetch_assoc()) {
        $doctores[] = [
            'id' => $row['ID'],
            'nombre' => $row['nombre']
        ];
    }
    $stmt->close();
    echo json_encode(['success' => true, 'data' => $doctores]);
    $conexion->close();
    exit;
}

if ($accion === 'get_dias_disponibles_doctor') {
    $doctor = $_GET['doctor'] ?? '';
    $sucursal = $_GET['sucursal'] ?? '';
    if (!$doctor || !$sucursal) {
        echo json_encode(['success' => false, 'data' => []]);
        $conexion->close();
        exit;
    }
    $sql = "SELECT DISTINCT ch.Fecha
            FROM consultorio_horario ch
            INNER JOIN consultorio c ON c.ID = ch.Consultorio_ID
            WHERE ch.Doctor_ID = ? AND c.Sucursal = ? AND ch.Disponible = 1";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ii", $doctor, $sucursal);
    $stmt->execute();
    $result = $stmt->get_result();
    $fechas = [];
    while ($row = $result->fetch_assoc()) {
        $fechas[] = $row['Fecha'];
    }
    $stmt->close();
    echo json_encode(['success' => true, 'data' => $fechas]);
    $conexion->close();
    exit;
}

if ($accion === 'get_horas_disponibles_doctor') {
    $doctor = $_GET['doctor'] ?? '';
    $sucursal = $_GET['sucursal'] ?? '';
    $fecha = $_GET['fecha'] ?? '';
    if (!$doctor || !$sucursal || !$fecha) {
        echo json_encode(['success' => false, 'data' => []]);
        $conexion->close();
        exit;
    }
    // Solo horarios disponibles ese día
    $sql = "SELECT ch.Hora
            FROM consultorio_horario ch
            INNER JOIN consultorio c ON c.ID = ch.Consultorio_ID
            WHERE ch.Doctor_ID = ? AND c.Sucursal = ? AND ch.Fecha = ? AND ch.Disponible = 1";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("iis", $doctor, $sucursal, $fecha);
    $stmt->execute();
    $result = $stmt->get_result();
    $horasDisponibles = [];
    while ($row = $result->fetch_assoc()) {
        $horasDisponibles[] = $row['Hora'];
    }
    $stmt->close();

    echo json_encode(['success' => true, 'data' => $horasDisponibles]);
    $conexion->close();
    exit;
}

if ($accion === 'insertar_cita') {
    session_start();
    // var_dump($_SESSION); // Puedes quitar esto si ya no lo necesitas
    $data = json_decode(file_get_contents('php://input'), true);
    $doctor = $data['doctor'] ?? '';
    $fecha = $data['fecha'] ?? '';
    $hora = $data['hora'] ?? '';
    $motivo = $data['motivo'] ?? '';
    $paciente = $_SESSION['usuario_persona'] ?? '';
    $estado = 1; // Por ejemplo: 1 = pendiente

    if (!$paciente) {
        echo json_encode(['success' => false, 'message' => 'Sesión no válida. Debe iniciar sesión.']);
        $conexion->close();
        exit;
    }

    // 1. Buscar el consultorio correspondiente y que esté disponible
    $sql = "SELECT Consultorio_ID FROM consultorio_horario WHERE Doctor_ID = ? AND Fecha = ? AND Hora = ? AND Disponible = 1";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("iss", $doctor, $fecha, $hora);
    $stmt->execute();
    $stmt->bind_result($consultorio_id);
    if ($stmt->fetch()) {
        $stmt->close();

        // 2. Insertar en cita
        $sql2 = "INSERT INTO cita (Consultorio_ID, Doctor_ID, Paciente_ID, Estado, Fecha_Emision, Fecha, Hora) VALUES (?, ?, ?, ?, NOW(), ?, ?)";
        $stmt2 = $conexion->prepare($sql2);
        $stmt2->bind_param("iiiiss", $consultorio_id, $doctor, $paciente, $estado, $fecha, $hora);
        if ($stmt2->execute()) {
            $cita_id = $stmt2->insert_id;
            $stmt2->close();

            // 3. Insertar motivo en consulta
            $sql3 = "INSERT INTO consulta (Cita_ID, Motivo, Fecha_Registro) VALUES (?, ?, NOW())";
            $stmt3 = $conexion->prepare($sql3);
            $stmt3->bind_param("is", $cita_id, $motivo);
            $stmt3->execute();
            $stmt3->close();

            // 4. Marcar horario como no disponible
            $sql4 = "UPDATE consultorio_horario SET Disponible = 0 WHERE Doctor_ID = ? AND Fecha = ? AND Hora = ?";
            $stmt4 = $conexion->prepare($sql4);
            $stmt4->bind_param("iss", $doctor, $fecha, $hora);
            $stmt4->execute();
            $stmt4->close();

            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se pudo guardar la cita']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No se encontró el consultorio disponible para ese horario']);
    }
    $conexion->close();
    exit;
}

echo json_encode(['success' => false, 'message' => 'Acción no válida']);
$conexion->close();
exit;
?>