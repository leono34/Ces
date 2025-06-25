<?php
// Conexión a la base de datos
$host = "localhost";
$user = "root";
$password = "";
$database = "incidencias";

$conn = new mysqli($host, $user, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if (isset($_POST["editar"])) {
    $archivo = '';

    // Procesar archivo subido (si hay uno nuevo)
    if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] == 0) {
        $archivo = time() . '_' . basename($_FILES['archivo']['name']);
        move_uploaded_file($_FILES['archivo']['tmp_name'], '../uploads/' . $archivo);
    } else {
        // Si no se subió archivo nuevo, obtener el archivo actual desde la BD
        $stmtArchivo = $conn->prepare("SELECT archivo FROM incidencia WHERE id_incidencia = ?");
        $stmtArchivo->bind_param("i", $_POST["id_incidencia"]);
        $stmtArchivo->execute();
        $stmtArchivo->bind_result($archivo_actual);
        $stmtArchivo->fetch();
        $archivo = $archivo_actual;
        $stmtArchivo->close();
    }

    // Preparar sentencia UPDATE con todos los campos
    $stmt = $conn->prepare("
        UPDATE incidencia 
        SET 
            id_cliente = ?, 
            id_estado = ?, 
            titulo = ?, 
            descripcion = ?, 
            id_prioridad = ?, 
            fecha_inicio = ?, 
            fecha_fin = ?, 
            comentarios = ?, 
            archivo = ?
        WHERE id_incidencia = ?
    ");

    if (!$stmt) {
        die("Error al preparar: " . $conn->error);
    }

    // Asignar correctamente los parámetros
    $stmt->bind_param(
        "iisssssssi", 
        $_POST["id_cliente"],
        $_POST["id_estado"],
        $_POST["titulo"],
        $_POST["descripcion"],
        $_POST["id_prioridad"],
        $_POST["fecha_inicio"],
        $_POST["fecha_fin"],
        $_POST["comentario"], 
        $archivo,
        $_POST["id_incidencia"]
    );

    if (!$stmt->execute()) {
        die("Error al ejecutar: " . $stmt->error);
    }

    $stmt->close();
    header("Location: /programacion/login/index.php");
    exit();
}
?>
