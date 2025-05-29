
<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    header('Content-Type: application/json');

    function GetConsultas($fecha) { 
        require_once '../confBD.php';
        $conexion = Conectarse();

        $sql = "SELECT c.ID, DATE_FORMAT(c.Fecha_Inicio, '%H:%i') as hora, p.Nombre, p.Ape_Pa, p.Ape_Ma FROM cita c
                INNER JOIN persona p ON p.ID = c.Paciente_ID
                WHERE Doctor_ID = {$_SESSION['persona_id']}
                    AND DATE(Fecha_Inicio) = '{$fecha}'";

        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $citas = [];

        while ($fila = $resultado->fetch_assoc()) {
            $citas[] = $fila;
        }

        if (count($citas) > 0) {
            return $citas;
        } else {
            return ["error" => "No se encontraron citas."];
        }
    }
    if(!isset($_GET['fecha']))
        echo json_encode(["error" => "La fecha no esta especificada"]);

        echo json_encode(GetConsultas($_GET['fecha']));
?>