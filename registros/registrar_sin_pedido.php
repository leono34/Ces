<?php

include("../login/CONEXIONBD.PHP");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $descripcion = $_POST['descripcion'];
    $fecha_reporte = $_POST['fecha_reporte'];

    // Verificar si los campos no están vacíos
    if (empty($descripcion) || empty($fecha_reporte)) {
        echo "❌ Todos los campos son obligatorios.";
    } else {
        // Inserción en la base de datos
        $query = "INSERT INTO casos_sin_pedido (descripcion, fecha_reporte) VALUES (?, ?)";
        $stmt = mysqli_prepare($conexion, $query);
        
        if ($stmt === false) {
            die("Error en la preparación de la consulta: " . mysqli_error($conexion));
        }
        
        mysqli_stmt_bind_param($stmt, "ss", $descripcion, $fecha_reporte);
        
        if (mysqli_stmt_execute($stmt)) {
            echo "🚧 Caso sin pedido registrado correctamente.";
        } else {
            echo "❌ Error al registrar el caso: " . mysqli_error($conexion);
        }
        
        mysqli_stmt_close($stmt);
    }
}
?>

<h2>Registrar caso sin pedido</h2>
<form method="POST">
    <input type="text" name="descripcion" placeholder="Descripción del caso" required>
    <input type="date" name="fecha_reporte" required>
    <button type="submit">Guardar caso</button>
</form>
