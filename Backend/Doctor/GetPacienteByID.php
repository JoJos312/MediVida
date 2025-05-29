
<?php
    header('Content-Type: application/json');

    function GetPacienteByID($id){ 
        require_once '../confBD.php';
        $conexion = Conectarse();
        $sql = "SELECT Nombre,Ape_Pa,Ape_Ma,Telefono,Fecha_Nacimiento FROM persona
                WHERE ID = ?";

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
        echo json_encode(GetPacienteByID($_GET['ID']));
    } else {
        echo json_encode(["error" => "ID no especificado"]);
    }
?>