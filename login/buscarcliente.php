<?php
header('Content-Type: application/json');

// Conexión a la base de datos
$host = "localhost";
$user = "root";
$password = "";
$database = "incidencias";

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die(json_encode(['error' => 'Conexión fallida: ' . $conn->connect_error]));
}

// Obtener datos de GET
$tipo = $_GET['tipo'] ?? '';
$valor = $_GET['valor'] ?? '';

if ($tipo && $valor) {
    if ($tipo === 'dni') {
        $stmt = $conn->prepare("SELECT * FROM clientes WHERE dni = ?");
    } else if ($tipo === 'correo') {
        $stmt = $conn->prepare("SELECT * FROM clientes WHERE correo = ?");
    } else {
        echo json_encode(['error' => 'Tipo de búsqueda no válido']);
        exit();
    }

    $stmt->bind_param("s", $valor);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        echo json_encode([
            'existe' => true,
            'nombres' => $row['nombres'],
            'apellido_paterno' => $row['apellido_paterno'],
            'apellido_materno' => $row['apellido_materno'],
            'dni' => $row['dni'],
            'correo' => $row['correo'],
            'telefono' => $row['telefono'],
            'direccion' => $row['direccion'],
            'id_empresa' => $row['id_empresa']
        ]);
    } else {
        echo json_encode(['existe' => false]);
    }
} else {
    echo json_encode(['error' => 'Datos incompletos']);
}
?>
    