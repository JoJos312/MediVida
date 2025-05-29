
<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    header('Content-Type: application/json');

    function GetConsultas($id_paciente,$page){ 
        require_once '../confBD.php';
        $conexion = Conectarse();
        $page -= 1;
        $page *= 10;
        $sql = "SELECT c.ID, co.Fecha_Registro as Fecha FROM cita c
                INNER JOIN consulta co ON co.Cita_ID = c.ID
                WHERE c.Doctor_ID = {$_SESSION['persona_id']} AND c.Paciente_ID = ?
                ORDER BY co.Fecha_Registro DESC
                LIMIT ?,10"; // 10 registros por pagina

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ii", $id_paciente, $page);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $citas = [];

        while ($fila = $resultado->fetch_assoc()) {
            $citas[] = $fila;
        }

        if (count($citas) > 0) {
            return $citas;
        } else {
            return ["error" => "No se encontraron consultas."];
        }
    }
    if(!isset($_GET['id']))
        echo json_encode(["error" => "ID del paciente no especificado"]);

    if (isset($_GET['page'])) {
        echo json_encode(GetConsultas($_GET['id'],$_GET['page']));
    } else {
        echo json_encode(GetConsultas($_GET['id'],1));
    }
?>