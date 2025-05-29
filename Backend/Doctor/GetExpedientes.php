
<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    header('Content-Type: application/json');

    function GetExpedientes($page){ 
        require_once '../confBD.php';
        $conexion = Conectarse();
        $page -= 1;
        $page *= 10;
        $sql = "SELECT p.ID, p.Nombre, p.Ape_Pa, p.Ape_Ma, MAX(co.Fecha_Registro) as Fecha FROM cita c
                INNER JOIN consulta co ON co.Cita_ID = c.ID
                INNER JOIN persona p ON p.ID = c.Paciente_ID
                WHERE Doctor_ID = {$_SESSION['persona_id']}
                GROUP BY p.ID, p.Nombre, p.Ape_Pa, p.Ape_Ma
                LIMIT ?,10"; // 10 registros por pagina

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $page);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $pacientes = [];

        while ($fila = $resultado->fetch_assoc()) {
            $pacientes[] = $fila;
        }

        if (count($pacientes) > 0) {
            return $pacientes;
        } else {
            return ["error" => "No se encontraron pacientes."];
        }
    }
    if (isset($_GET['page'])) {
        echo json_encode(GetExpedientes($_GET['page']));
    } else {
        echo json_encode(GetExpedientes(1));
    }
?>