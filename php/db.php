<?php
$conn = new mysqli("localhost", "root", "", "incidencias");
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
