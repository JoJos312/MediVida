<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../../Backend/confBD.php';

if (!isset($_SESSION['usuario_persona'])) {
    header("Location: ../login.html");
    exit();
}

$personaID = $_SESSION['usuario_persona'];
$conexion = Conectarse();

// Obtener nombre
$stmt = $conexion->prepare("SELECT Nombre FROM persona WHERE ID = ?");
$stmt->bind_param("i", $personaID);
$stmt->execute();
$resultado = $stmt->get_result();
$nombrePaciente = "Usuario";

if ($resultado->num_rows === 1) {
    $fila = $resultado->fetch_assoc();
    $nombrePaciente = $fila['Nombre'];
}
$stmt->close();


