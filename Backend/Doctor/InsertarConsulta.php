<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../confBD.php'; // Tu archivo de conexión
$conexion = Conectarse();

// Verifica que los campos requeridos vengan por POST
if (
    isset($_POST['id']) &&
    isset($_POST['motivo']) &&
    isset($_POST['diagnostico']) &&
    isset($_POST['receta']) &&
    isset($_POST['detalles'])
) {
    $id = $_POST['id'];
    $motivo = $_POST['motivo'];
    $diagnostico = $_POST['diagnostico'];
    $receta = $_POST['receta'];
    $detalles = $_POST['detalles'];

    // Validaciones de los datos
    if($id == "" || !is_numeric($id) ){
        echo json_encode(["error" => "El ID [{$id}] de la cita es invalida"]);
    }

    $stmt = $conexion->prepare("INSERT INTO consulta (Cita_ID,Motivo,Diagnostico,Tratamiento,Fecha_Registro,Notas) VALUES (?, ?, ?, ?,NOW(),?)");
    $stmt->bind_param("issss",$id,$motivo,$diagnostico,$receta,$detalles);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "mensaje" => "Consulta insertado correctamente"]);
    } else {
        echo json_encode(["error" => "Error al insertar: " . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["error" => "Faltan datos requeridos (id cita, motivo, diagnostico, receta, detalles)"]);
}
?>