<?php

require_once 'confBD.php';
header('Content-Type: application/json');

$conexion = Conectarse();

// Lee el input JSON solo una vez
$rawInput = file_get_contents('php://input');
$data = $_POST;
if (empty($data) && $rawInput) {
    $data = json_decode($rawInput, true);
}

$accion = $_GET['accion'] ?? $_POST['accion'] ?? $data['accion'] ?? '';

// Obtener especialidades
if ($accion === 'get_especialidades') {
    $result = $conexion->query("SELECT id, nombre FROM especialidad");
    $especialidades = [];
    while ($row = $result->fetch_assoc()) {
        $especialidades[] = $row;
    }
    echo json_encode(['success' => true, 'data' => $especialidades]);
    $conexion->close();
    exit;
}

// Registrar doctor
if ($accion === 'registrar_doctor') {
    $data = $_POST;
    if (empty($data) && file_get_contents('php://input')) {
        $data = json_decode(file_get_contents('php://input'), true);
    }

    $nombre = $data['nombre'] ?? '';
    $apep = $data['apep'] ?? '';
    $apem = $data['apem'] ?? '';
    $curp = $data['curp'] ?? '';
    $rfc = $data['rfc'] ?? '';
    $telefono = $data['telefono'] ?? '';
    $fecha_nacimiento = $data['fecha_nacimiento'] ?? '';
    $especialidad_id = $data['especialidad_id'] ?? '';
    $correo = $data['correo'] ?? '';
    $contrasena = $data['contrasena'] ?? '';

    if (!$nombre || !$apep || !$curp || !$rfc || !$telefono || !$fecha_nacimiento || !$especialidad_id || !$correo || !$contrasena) {
        echo json_encode(['success' => false, 'message' => 'Faltan datos obligatorios']);
        $conexion->close();
        exit;
    }

    // 1. Insertar persona
    $stmt = $conexion->prepare("INSERT INTO persona (Nombre, Ape_Pa, Ape_Ma, CURP, RFC, Telefono, Fecha_Nacimiento) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $nombre, $apep, $apem, $curp, $rfc, $telefono, $fecha_nacimiento);
    if (!$stmt->execute()) {
        echo json_encode(['success' => false, 'message' => 'Error al registrar persona']);
        $stmt->close();
        $conexion->close();
        exit;
    }
    $persona_id = $conexion->insert_id;
    $stmt->close();

    // 2. Insertar usuario (rol 2 = doctor)
    $contrasena_hash = $contrasena;
    $rol = 2;
    $stmt = $conexion->prepare("INSERT INTO usuario (Correo, Contrasena_Hash, Rol, Persona) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssii", $correo, $contrasena_hash, $rol, $persona_id);
    if (!$stmt->execute()) {
        echo json_encode(['success' => false, 'message' => 'Error al registrar usuario']);
        $stmt->close();
        $conexion->close();
        exit;
    }
    $stmt->close();

    // 3. Insertar en doctor_especialidades
    $stmt = $conexion->prepare("INSERT INTO doctor_especialidades (Especialidad, Doctor_ID) VALUES (?, ?)");
    $stmt->bind_param("ii", $especialidad_id, $persona_id);
    if (!$stmt->execute()) {
        echo json_encode(['success' => false, 'message' => 'Error al registrar especialidad del doctor']);
        $stmt->close();
        $conexion->close();
        exit;
    }
    $stmt->close();

    echo json_encode(['success' => true, 'message' => 'Doctor registrado correctamente']);
    $conexion->close();
    exit;
}

// Obtener doctores
if ($accion === 'get_doctores') {
    $sql = "SELECT 
                p.Nombre, p.Ape_Pa, p.Ape_Ma, p.CURP, p.RFC, p.Telefono, p.Fecha_Nacimiento,
                e.nombre AS especialidad, u.Correo
            FROM persona p
            INNER JOIN usuario u ON u.Persona = p.id
            INNER JOIN doctor_especialidades de ON de.Doctor_ID = p.id
            INNER JOIN especialidad e ON e.id = de.Especialidad
            WHERE u.Rol = 2";
    $result = $conexion->query($sql);
    $doctores = [];
    while ($row = $result->fetch_assoc()) {
        $doctores[] = $row;
    }
    echo json_encode(['success' => true, 'data' => $doctores]);
    $conexion->close();
    exit;
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

// Obtener consultorios de una sucursal
if ($accion === 'get_consultorios') {
    $sucursal = $_GET['sucursal'] ?? $_POST['sucursal'] ?? $data['sucursal'] ?? '';
    if (!$sucursal) {
        echo json_encode(['success' => false, 'message' => 'Falta sucursal']);
        $conexion->close();
        exit;
    }
    $stmt = $conexion->prepare("SELECT Numero FROM consultorio WHERE Sucursal = ?");
    $stmt->bind_param("i", $sucursal);
    $stmt->execute();
    $stmt->bind_result($numero);
    $consultorios = [];
    while ($stmt->fetch()) {
        $consultorios[] = $numero;
    }
    $stmt->close();
    echo json_encode(['success' => true, 'data' => $consultorios]);
    $conexion->close();
    exit;
}

// Asignar horario a doctor
if ($accion === 'asignar_horario_doctor') {
    $doctor_correo = $data['doctor_correo'] ?? '';
    $sucursal = $data['sucursal'] ?? '';
    $consultorio = $data['consultorio'] ?? '';
    $fecha = $data['fecha'] ?? '';
    $hora = $data['hora'] ?? '';

    if (!$doctor_correo || !$sucursal || !$consultorio || !$fecha || !$hora) {
        echo json_encode(['success' => false, 'message' => 'Faltan datos']);
        $conexion->close();
        exit;
    }

    // Obtener el ID del doctor
    $stmt = $conexion->prepare("SELECT p.ID FROM persona p INNER JOIN usuario u ON u.Persona = p.ID WHERE u.Correo = ? AND u.Rol = 2");
    $stmt->bind_param("s", $doctor_correo);
    $stmt->execute();
    $stmt->bind_result($doctor_id);
    if (!$stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Doctor no encontrado']);
        $stmt->close();
        $conexion->close();
        exit;
    }
    $stmt->close();

    // Obtener el ID del consultorio
    $stmt = $conexion->prepare("SELECT ID FROM consultorio WHERE Sucursal = ? AND Numero = ?");
    $stmt->bind_param("ii", $sucursal, $consultorio);
    $stmt->execute();
    $stmt->bind_result($consultorio_id);
    if (!$stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Consultorio no encontrado']);
        $stmt->close();
        $conexion->close();
        exit;
    }
    $stmt->close();

    // Verifica si ya existe ese horario para ese consultorio y fecha
    $stmt = $conexion->prepare("SELECT 1 FROM consultorio_horario WHERE Consultorio_ID=? AND Fecha=? AND Hora=?");
    $stmt->bind_param("iss", $consultorio_id, $fecha, $hora);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Ese horario ya está asignado']);
        $stmt->close();
        $conexion->close();
        exit;
    }
    $stmt->close();

    // Insertar el horario
    $stmt = $conexion->prepare("INSERT INTO consultorio_horario (Consultorio_ID, Fecha, Doctor_ID, Hora) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isis", $consultorio_id, $fecha, $doctor_id, $hora);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Horario asignado correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al asignar horario']);
    }
    $stmt->close();
    $conexion->close();
    exit;
}

// Obtener horarios ocupados de un consultorio en una fecha (con nombre del doctor)
if ($accion === 'get_horarios_ocupados') {
    $sucursal = $_GET['sucursal'] ?? '';
    $consultorio = $_GET['consultorio'] ?? '';
    $fecha = $_GET['fecha'] ?? '';

    if (!$sucursal || !$consultorio || !$fecha) {
        echo json_encode(['success' => false, 'data' => []]);
        $conexion->close();
        exit;
    }

    // Obtener el ID del consultorio
    $stmt = $conexion->prepare("SELECT ID FROM consultorio WHERE Sucursal = ? AND Numero = ?");
    $stmt->bind_param("ii", $sucursal, $consultorio);
    $stmt->execute();
    $stmt->bind_result($consultorio_id);
    if (!$stmt->fetch()) {
        echo json_encode(['success' => false, 'data' => []]);
        $stmt->close();
        $conexion->close();
        exit;
    }
    $stmt->close();

    // Buscar horarios ocupados para ese consultorio y fecha, con nombre del doctor
    $stmt = $conexion->prepare("
        SELECT ch.Hora, p.Nombre, p.Ape_Pa, p.Ape_Ma
        FROM consultorio_horario ch
        INNER JOIN persona p ON ch.Doctor_ID = p.ID
        WHERE ch.Consultorio_ID = ? AND ch.Fecha = ?
    ");
    $stmt->bind_param("is", $consultorio_id, $fecha);
    $stmt->execute();
    $result = $stmt->get_result();
    $horas = [];
    while ($row = $result->fetch_assoc()) {
        $horas[] = [
            'hora' => substr($row['Hora'], 0, 5),
            'doctor' => $row['Nombre'] . ' ' . $row['Ape_Pa'] . ' ' . $row['Ape_Ma']
        ];
    }
    $stmt->close();
    echo json_encode(['success' => true, 'data' => $horas]);
    $conexion->close();
    exit;
}

// Obtener eventos del calendario (horarios ocupados con doctor)
if ($accion === 'get_eventos_calendario') {
    $sucursal = $_GET['sucursal'] ?? '';
    $consultorio = $_GET['consultorio'] ?? '';

    if (!$sucursal || !$consultorio) {
        echo json_encode([]);
        $conexion->close();
        exit;
    }

    // Obtener el ID del consultorio
    $stmt = $conexion->prepare("SELECT ID FROM consultorio WHERE Sucursal = ? AND Numero = ?");
    $stmt->bind_param("ii", $sucursal, $consultorio);
    $stmt->execute();
    $stmt->bind_result($consultorio_id);
    if (!$stmt->fetch()) {
        echo json_encode([]);
        $stmt->close();
        $conexion->close();
        exit;
    }
    $stmt->close();

    // Obtener los días ocupados (uno por día)
    $query = "
        SELECT DISTINCT ch.Fecha
        FROM consultorio_horario ch
        WHERE ch.Consultorio_ID = $consultorio_id
    ";
    $result = $conexion->query($query);
    $eventos = [];
    while ($row = $result->fetch_assoc()) {
        $eventos[] = [
            'start' => $row['Fecha'],
            'display' => 'background',
            'allDay' => true,
            'backgroundColor' => '#ffcccc' // Cambia el color si quieres
        ];
    }
    echo json_encode($eventos);
    $conexion->close();
    exit;
}

echo json_encode(['success' => false, 'message' => 'Acción no válida']);
$conexion->close();
exit;
?>