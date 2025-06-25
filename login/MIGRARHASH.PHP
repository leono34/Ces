<?php
require_once "CONEXIONBD.PHP";
$db = obtenerConexion();

$usuarios = $db->query("SELECT id_usuario, contraseña FROM usuario");

if (!$usuarios) {
    die("Error en la consulta: " . $db->error);
}

while ($row = $usuarios->fetch_assoc()) {
    $id = $row['id_usuario'];
    $plain = trim($row['contraseña']);
    $hash = password_hash($plain, PASSWORD_DEFAULT);

    $upd = $db->prepare("UPDATE usuario SET contraseña = ? WHERE id_usuario = ?");
    $upd->bind_param("si", $hash, $id);
    $upd->execute();

    echo "🔄 Usuario ID $id: contraseña '$plain' → hash: $hash <br>";
}

echo "<br>Contraseñas rehasheadas.";
?>
