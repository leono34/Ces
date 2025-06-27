<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: /programacion/login/LOGIN.PHP");
    exit();
}

// ================= LÓGICA DE ELIMINACIÓN =================
include '../login/CONEXIONBD.php';
$conn = obtenerConexion();

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if (isset($_GET["eliminar"])) {
    $id = $_GET["eliminar"];

    $stmt = $conn->prepare("DELETE FROM clientes WHERE id_cliente = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION["mensaje"] = "Cliente eliminado correctamente.";
    } else {
        $_SESSION["mensaje"] = "Error al eliminar cliente.";
    }

    header("Location: index.php");
    exit();
}


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

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SISTEMA DE INCIDENCIAS</title>   
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <script src="../js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<body style ="padding: 0px;">
    <div class="contenedor">
        <div class="navegacion">
            <li>
                <a href="javascript:void(0);" class="enlace">
                    <img src="../fotos/logoMacro.png" alt="" class="logo">
                </a>
            </li>
            <ul>
                <li>
                    <a href="javascript:void(0);" class="cargar-contenido" data-url="../registros/dashboard.php">
                        <span class="icon">
                            <i class="bi bi-house-door"></i>
                        </span>
                        <span class="title">Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="javascript:void(0);" class="cargar-contenido" data-url="../registros/form_busc_regis.php">
                        <span class="icon"><i class="bi bi-search"></i></span>
                        <span class="title">Buscar/Registrar</span>
                    </a>
                </li>

                <li class="has-submenu">
                    <a href="javascript:void(0);" class="toggle-submenu">
                        <span class="icon">
                            <i class="bi bi-people"></i>
                        </span>
                        <span class="title">Incidencias</span>
                        <span class="arrow"><i class="bi bi-chevron-down"></i></span>
                    </a>
                    <ul class="submenu">
                        <li><a href="javascript:void(0);" class="cargar-contenido" data-url="../registros/form_busc_regis_incidencia.php">Registrar</a></li>
                       
                        <li><a href="javascript:void(0);" class="cargar-contenido" data-url="../registros/registrar_clien.php" href="" >Reportes</a></li>
                    </ul>
                </li>

                <li>
                    <a a href="javascript:void(0);" class="cargar-contenido" data-url="../registros/form_busc_seguimiento_incidencia.php">
                        <span class="icon"><i class="bi bi-clipboard-check"></i></span>
                        <span class="title">Seguimimento</span>             
                    </a>
                </li>

                <!-- <li>
                    <a href="javascript:void(0);" class="no-click">
                        <span class="icon">
                            <i class="bi bi-file-earmark-text"></i>
                        </span>
                        <span class="title">Servicio técnico</span>               
                    </a>
                </li> -->

                <li>
                    <a href="SALIR.PHP">
                        <span class="icon">
                            <i class="bi bi-box-arrow-in-right"></i>
                        </span>
                        <span class="title">Cerrar sesión</span>
                    </a>
                </li>
            </ul>
        </div>        
    </div>   
    <!-- =================Main====================== -->
    <div class="main">
        <div class="topbar">
            <div class="toggle">
                <i class="bi bi-justify"></i>
            </div>
        </div>
        <div id="contenido-dinamico" class="contenido-dinamico"></div>
    </div>
    
<?php
if (isset($_SESSION['error']) && $_SESSION['error'] == 'duplicado') {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'El DNI ingresado ya existe en la base de datos.'
        });
    </script>";
    unset($_SESSION['error']); // Eliminamos para que no se repita
}
?>
<script>
document.addEventListener('DOMContentLoaded', () => {
    fetch('../registros/dashboard.php')
        .then(res => res.text())
        .then(html => {
            const contenido = document.getElementById('contenido-dinamico');
            contenido.innerHTML = html;

            //  Llama a los gráficos después de que el HTML esté listo
            setTimeout(() => {
                cargarGraficosDashboard();
            }, 200);
        });
});

</script>
</body>
</html>

