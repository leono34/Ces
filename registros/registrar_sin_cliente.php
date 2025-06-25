<?php

include("../login/CONEXIONBD.PHP");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo_pedido = $_POST['codigo_pedido'];
    $comentarios = $_POST['comentarios'];

    // Verificar si los campos no están vacíos
    if (empty($comentarios)) {
        echo "❌ El campo comentarios es obligatorio.";
    } else {
        // Inserción en la base de datos
        $query = "INSERT INTO casos_sin_cliente (codigo_pedido, comentarios) VALUES (?, ?)";
        $stmt = mysqli_prepare($conexion, $query);
        
        if ($stmt === false) {
            die("Error en la preparación de la consulta: " . mysqli_error($conexion));
        }
        
        mysqli_stmt_bind_param($stmt, "ss", $codigo_pedido, $comentarios);
        
        if (mysqli_stmt_execute($stmt)) {
            echo "🚧 Caso sin cliente registrado correctamente.";
        } else {
            echo "❌ Error al registrar el caso: " . mysqli_error($conexion);
        }
        
        mysqli_stmt_close($stmt);
    }
}
?>

<h2>Registrar caso sin cliente</h2>
<form method="POST">
    <input type="text" name="codigo_pedido" placeholder="Código de pedido (si existe)">
    <textarea name="comentarios" placeholder="Comentarios o motivo del caso" required></textarea>
    <button type="submit">Guardar caso</button>
</form>
