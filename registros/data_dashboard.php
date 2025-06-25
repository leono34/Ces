<?php
header('Content-Type: application/json');

// Conexión a la base de datos
include '../login/CONEXIONBD.php';
$conn = obtenerConexion();

if ($conn->connect_error) {
    echo json_encode(["error" => "Conexión fallida: " . $conn->connect_error]);
    exit();
}

// Variables para respuesta
$response = [
    "total_incidencias" => 0,
    "total_clientes" => 0,
    "incidencias_pendientes" => 0,
    "incidencias_finalizadas" => 0,
    "prioridad" => [
        "alta" => 0,
        "media" => 0,
        "baja" => 0
    ],
    "meses" => [],
    "datos_mes" => [],
    "estados" => [],
    "datos_estados" => []
];

// Consultas generales
$response["total_incidencias"] = (int) $conn->query("SELECT COUNT(*) as total FROM incidencia")->fetch_assoc()['total'];
$response["total_clientes"] = (int) $conn->query("SELECT COUNT(*) as total FROM clientes")->fetch_assoc()['total'];
$response["incidencias_pendientes"] = (int) $conn->query("SELECT COUNT(*) as total FROM incidencia WHERE id_estado = 1")->fetch_assoc()['total'];
$response["incidencias_finalizadas"] = (int) $conn->query("SELECT COUNT(*) as total FROM incidencia WHERE id_estado = 4")->fetch_assoc()['total'];

$response["prioridad"]["alta"] = (int) $conn->query("SELECT COUNT(*) as total FROM incidencia WHERE id_prioridad = 1")->fetch_assoc()['total'];
$response["prioridad"]["media"] = (int) $conn->query("SELECT COUNT(*) as total FROM incidencia WHERE id_prioridad = 2")->fetch_assoc()['total'];
$response["prioridad"]["baja"] = (int) $conn->query("SELECT COUNT(*) as total FROM incidencia WHERE id_prioridad = 3")->fetch_assoc()['total'];

// Incidencias por mes
$resMes = $conn->query("
    SELECT 
        MONTH(fecha_inicio) as mes,
        MONTHNAME(fecha_inicio) as nombre_mes,
        COUNT(*) as total 
    FROM incidencia 
    WHERE fecha_inicio >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
    GROUP BY MONTH(fecha_inicio), MONTHNAME(fecha_inicio)
    ORDER BY MONTH(fecha_inicio)
");

while($row = $resMes->fetch_assoc()) {
    $response["meses"][] = substr($row['nombre_mes'], 0, 3);
    $response["datos_mes"][] = (int) $row['total'];
}

// Estados
$resEstados = $conn->query("
    SELECT e.nombre_estado, COUNT(i.id_incidencia) as total
    FROM estados_reclamos e
    LEFT JOIN incidencia i ON e.id_estado = i.id_estado
    GROUP BY e.id_estado, e.nombre_estado
");

while($row = $resEstados->fetch_assoc()) {
    $response["estados"][] = $row['nombre_estado'];
    $response["datos_estados"][] = (int) $row['total'];
}

// Respuesta final
echo json_encode($response);
?>
