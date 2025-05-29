
<?php
    header('Content-Type: application/json');

    function GetConsultaByCitaID($id){ 
        require_once '../confBD.php';
        $conexion = Conectarse();
        $sql = "SELECT p.Nombre, p.Ape_Pa, p.Ape_Ma, co.Motivo,co.Diagnostico,co.Tratamiento,co.Notas FROM cita c
                INNER JOIN consulta co ON co.Cita_ID = c.ID
                INNER JOIN persona p ON p.ID = c.Paciente_ID
                WHERE c.ID = ?";

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        if ($resultado->num_rows > 0) {
            $persona = $resultado->fetch_assoc();
            return $persona;
        } else {
            return ["error" => "Paciente no encontrado."];
        }
    }
    if (isset($_GET['ID'])) {
        echo json_encode(GetConsultaByCitaID($_GET['ID']));
    } else {
        echo json_encode(["error" => "ID no especificado"]);
    }
?>