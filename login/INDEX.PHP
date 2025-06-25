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
                        <!-- href="../registros/registrar_clien.php" -->
                        <li><a >Reportes - Proximamente</a></li>
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
    fetch('../registros/dashboard.php')
  .then(res => res.text())
  .then(html => {
    const contenedor = document.getElementById('contenido-dinamico');
    contenedor.innerHTML = html;

    // Ejecutar scripts embebidos
    const scripts = contenedor.querySelectorAll('script');
    scripts.forEach(oldScript => {
      const newScript = document.createElement('script');
      if (oldScript.src) {
        newScript.src = oldScript.src;
        newScript.async = false; // Importante para mantener el orden
        document.body.appendChild(newScript);
        console.log("script externo cargado:", oldScript.src);
      } else {
        newScript.textContent = oldScript.textContent;
        document.body.appendChild(newScript);
        console.log("script inline ejecutado");
      }
    });

    // 💡 Cargar dashboard.js manualmente si es necesario
    const scriptExistente = document.querySelector('script[src="../js/dashboard.js"]');
    if (!scriptExistente) {
      const scriptDashboard = document.createElement('script');
      scriptDashboard.src = '../js/dashboard.js';
      scriptDashboard.async = false;
      document.body.appendChild(scriptDashboard);
    }
  });

</script>
</body>
</html>

