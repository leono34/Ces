
<?php
// Conexión a la base de datos (ajusta estos valores)
$host = "localhost";
$user = "root";
$password = "";
$database = "incidencias";

$conn = new mysqli($host, $user, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta
$sql = "SELECT id_empresa, nombre FROM empresas";
$result = $conn->query($sql);

// Consulta
$sql = "SELECT id, nombre FROM tipo_busqueda";
$result_tipo_busqueda = $conn->query($sql);

// Consulta
$sql = "SELECT id_cliente, CONCAT_WS(' ', nombres, apellido_paterno, apellido_materno) AS dato
FROM clientes;";
$result_cliente = $conn->query($sql);

if (isset($_POST["insertar"])) {
    //$nombre = $_POST['nombre'];
    $archivo = '';

    // Verifica si hay archivo subido
    if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === 0) {
            // Validar extensión
            $fileType = mime_content_type($_FILES['archivo']['tmp_name']);
            if ($fileType !== 'application/pdf') {
                die("Solo se permiten archivos PDF.");
            }
        // Asegura que el directorio "uploads/" exista
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Prepara el nombre del archivo para evitar duplicados
        $archivoNombre = time() . '_' . basename($_FILES['archivo']['name']);
        $archivoRuta = $uploadDir . $archivoNombre;

        // Mueve el archivo al directorio
        if (move_uploaded_file($_FILES['archivo']['tmp_name'], $archivoRuta)) {
            $archivo = $archivoNombre;
        }
    }
    // Valor en 1 - Ingresado por defecto
    $id_estado = 1;

    $stmt = $conn->prepare(
        "INSERT INTO incidencia (id_cliente,titulo,descripcion,id_prioridad,fecha_inicio,fecha_fin,archivo,id_estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
    );
    if (!$stmt) {
        die("Error en prepare: " . $conn->error);
    }

    $stmt->bind_param(
        "issssssi",
        $_POST["id_cliente"],
        $_POST["titulo"],
        $_POST["descripcion"],
        $_POST["id_prioridad"],
        $_POST["fecha_inicio"],
        $_POST["fecha_fin"],
        $archivo,
        $id_estado
    );

    if (!$stmt->execute()) {
        die("Error al insertar: " . $stmt->error);
    }

    // Redirigir con indicador
    header("Location: /programacion/login/index.php");
    exit();
}



// Actualizar o Editar los datos



if (isset($_POST["editar"])) {
    $archivo = '';

    // 1. Procesar archivo primero
    if ($_FILES['archivo']['error'] == 0) {
        $archivo = time() . '_' . basename($_FILES['archivo']['name']);
        move_uploaded_file($_FILES['archivo']['tmp_name'], 'uploads/' . $archivo);
        // Validar extensión
        $fileType = mime_content_type('uploads/' . $archivo);
        if ($fileType !== 'application/pdf') {
            die("Solo se permiten archivos PDF.");
        }
        
    } else {
        // Si no se subió archivo nuevo, conservar el anterior
        $stmtArchivo = $conn->prepare("SELECT archivo FROM incidencia WHERE id_incidencia = ?");
        $stmtArchivo->bind_param("i", $_POST["id_incidencia"]);
        $stmtArchivo->execute();
        $stmtArchivo->bind_result($archivo_actual);
        $stmtArchivo->fetch();
        $archivo = $archivo_actual;
        $stmtArchivo->close();
    }

    $stmt = $conn->prepare(
        "UPDATE incidencia SET id_cliente=?, titulo=?, descripcion=?, id_prioridad=?, id_estado=?, fecha_inicio=?, fecha_fin=?, archivo=? WHERE id_incidencia=?"
    );

    if (!$stmt) {
        die("Error al preparar: " . $conn->error);
    }

    $stmt->bind_param(
        "ssssisssi", // Se añadió 'i' para id_estado
        $_POST["id_cliente"],
        $_POST["titulo"],
        $_POST["descripcion"],
        $_POST["id_prioridad"],
        $_POST["id_estado"], // Nuevo campo
        $_POST["fecha_inicio"],
        $_POST["fecha_fin"],
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

// Eliminar
if (isset($_GET["eliminar"])) {
    $id = intval($_GET["eliminar"]); // Sanitizamos

    // Usar sentencia preparada por seguridad
    $stmt = $conn->prepare("DELETE FROM incidencia WHERE id_incidencia = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Redirige para evitar reenvío
        header("Location: /programacion/login/index.php?eliminado=1");
        exit();
    } else {
        die("Error al eliminar: " . $stmt->error);
    }

    $stmt->close();
}


$sql_base = "SELECT i.*, e.nombre_estado , CONCAT_WS(' ', c.nombres, c.apellido_paterno, c.apellido_materno) AS nombre_cliente,
    p.descripcion AS nombre_prioridad
    FROM incidencia i
    JOIN clientes c ON i.id_cliente = c.id_cliente
    JOIN prioridad p ON i.id_prioridad = p.id_prioridad
    JOIN estados_reclamos e ON i.id_estado = e.id_estado";

$condiciones = [];
$tipos_param = "";
$valores_param = [];

if (isset($_GET['tipo_filtro']) && !empty($_GET['tipo_filtro'])) {
    $tipo_filtro = $_GET['tipo_filtro'];

    if ($tipo_filtro === 'dni_cliente' && isset($_GET['valor_filtro']) && !empty($_GET['valor_filtro'])) {
        $condiciones[] = "c.dni = ?";
        $tipos_param .= "s";
        $valores_param[] = $_GET['valor_filtro'];
    } elseif ($tipo_filtro === 'prioridad_alta') {
        // Asumiendo que la descripción de la prioridad alta es 'Alta'.
        // Esto debería confirmarse con la estructura de la tabla `prioridad`.
        // Si es un ID, sería algo como p.id_prioridad = 1 (o el ID correspondiente)
        $condiciones[] = "p.descripcion = ?";
        $tipos_param .= "s";
        $valores_param[] = "Alta"; // O el valor que corresponda a prioridad alta
    }
}

if (!empty($condiciones)) {
    $sql_base .= " WHERE " . implode(" AND ", $condiciones);
}

$sql_base .= " ORDER BY i.id_incidencia";

$stmt_lista = $conn->prepare($sql_base);

if ($stmt_lista) {
    if (!empty($valores_param)) {
        $stmt_lista->bind_param($tipos_param, ...$valores_param);
    }
    $stmt_lista->execute();
    $result_lista = $stmt_lista->get_result();
} else {
    // Manejo de error si la preparación de la consulta falla
    die("Error al preparar la consulta de lista de incidencias: " . $conn->error);
}

// $result_lista = $conn->query($sql); // Línea original comentada o eliminada

if (!$result_lista) {
    die("Error en la consulta: " . $conn->error);
}




// Consulta para obtener todas las incidencia
$result_lista_2 = $conn->query("SELECT * FROM incidencia");





?>