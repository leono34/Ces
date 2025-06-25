<?php
session_start();
// Conexión a la base de datos (ajusta estos valores)
include '../login/CONEXIONBD.php';
$conn = obtenerConexion();

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// cliente con empresa 
$sql = "SELECT c.*, e.nombre AS nombre_empresa
        FROM clientes c
        LEFT JOIN empresas e ON c.id_empresa = e.id_empresa";
        $clientes = [];
$result_empresa = $conn->query($sql);
while($row = $result_empresa->fetch_assoc()){
    $clientes[] = $row;
}




// Consulta
$sql = "SELECT id_empresa, nombre FROM empresas";
$result_empresas = $conn->query($sql);


//inserta 
if (isset($_POST["insertar"])) {
    $stmt = $conn->prepare(
        "INSERT INTO clientes (nombres, apellido_paterno, apellido_materno, dni, correo, telefono, direccion,id_empresa) VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
    );
    if (!$stmt) {
        die("Error en prepare: " . $conn->error);
    }

    $stmt->bind_param(
        "ssssssss",
        $_POST["nombres"],
        $_POST["apellido_paterno"],
        $_POST["apellido_materno"],
        $_POST["dni"],
        $_POST["correo"],
        $_POST["telefono"],
        $_POST["direccion"],
        $_POST["id_empresa"]
    );

    if (!$stmt->execute()) {
       // Si error por DNI duplicado:
        if ($conn->errno == 1062) {
        $_SESSION['error'] = 'duplicado'; // Guardamos en sesión
        header("Location: /programacion/login/index.php");
        exit();
        } else {
            die("Error al insertar: " . $stmt->error);
        }
    }

    // Redirige para evitar reenvío del formulario
    header("Location: /programacion/login/index.php");

    exit();

}

// Editar
if (isset($_POST["editar"])) {
    $stmt = $conn->prepare(
        "UPDATE clientes SET nombres=?, apellido_paterno=?, apellido_materno=?, dni=?, correo=?, telefono=?, direccion=?, id_empresa=? WHERE id_cliente=?"
    );

    if (!$stmt) {
        die("Error al preparar: " . $conn->error);
    }

    $stmt->bind_param(
        "sssssssii",
        $_POST["nombres"],
        $_POST["apellido_paterno"],
        $_POST["apellido_materno"],
        $_POST["dni"],
        $_POST["correo"],
        $_POST["telefono"],
        $_POST["direccion"],
        $_POST["id_empresa"],
        $_POST["id_cliente"]
    );

    if (!$stmt->execute()) {
        die("Error al ejecutar: " . $stmt->error);
    }

    // Redirigir con indicador
    header("Location: /programacion/login/index.php");
    exit();
}


// Eliminar

if (isset($_GET["eliminar"])) {
    $id = $_GET["eliminar"];
    $stmt = $conn->prepare("DELETE FROM clientes WHERE id_cliente = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: /programacion/login/index.php");
    exit();
}


$result_lista = $conn->query("SELECT * FROM clientes");
$result_lista2 = $conn->query("SELECT * FROM clientes");

?>