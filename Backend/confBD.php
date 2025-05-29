<?php
function Conectarse() {
    $host = "localhost";       // Dirección del servidor MySQL
    $puerto = "3306";          // Cambiar al puerto 3307 si es necesario
    $usuario = "root";         // Usuario de MySQL
    $contrasena = "";          // Contraseña de MySQL
    $baseDeDatos = "medivida"; // Nombre de la base de datos

    $conexion = new mysqli($host, $usuario, $contrasena, $baseDeDatos, $puerto);

    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    return $conexion;
}
?>