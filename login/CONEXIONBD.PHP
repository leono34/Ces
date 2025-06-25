<?php
function obtenerConexion() {
    $host = "localhost";
    $usuario = "root"; // No usar root
    $clave = ""; // Agregar contraseña
    $bd = "incidencias";

    $conexion = new mysqli($host, $usuario, $clave, $bd);

    if ($conexion->connect_error) {
        error_log("Error de conexión: " . $conexion->connect_error);
        die("Error al conectar con la base de datos.");
    }

    return $conexion;
}
?>