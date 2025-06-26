<?php
// Conexión a la base de datos (ajusta estos valores)
$host = "localhost";
$user = "root";
$password = "";
$database = "incidencias";

$conn = new mysqli($host, $user, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    // Considerar devolver un error HTTP adecuado o un JSON de error
    die("Conexión fallida: " . $conn->connect_error);
}

$output = ''; // Variable para almacenar el HTML de las filas

$sql_base = "SELECT i.*, e.nombre_estado , CONCAT_WS(' ', c.nombres, c.apellido_paterno, c.apellido_materno) AS nombre_cliente,
    p.descripcion AS nombre_prioridad
    FROM incidencia i
    JOIN clientes c ON i.id_cliente = c.id_cliente
    JOIN prioridad p ON i.id_prioridad = p.id_prioridad
    JOIN estados_reclamos e ON i.id_estado = e.id_estado";

$condiciones = [];
$tipos_param = "";
$valores_param = [];

// Se espera que los parámetros vengan por GET desde la petición fetch
if (isset($_GET['tipo_filtro']) && !empty($_GET['tipo_filtro'])) {
    $tipo_filtro = $_GET['tipo_filtro'];

    if ($tipo_filtro === 'dni_cliente' && isset($_GET['valor_filtro']) && !empty($_GET['valor_filtro'])) {
        $condiciones[] = "c.dni = ?";
        $tipos_param .= "s";
        $valores_param[] = $_GET['valor_filtro'];
    } elseif ($tipo_filtro === 'prioridad' && isset($_GET['valor_filtro']) && !empty($_GET['valor_filtro'])) {
        $condiciones[] = "p.descripcion LIKE ?"; // Usar LIKE para más flexibilidad si se desea
        $tipos_param .= "s";
        $valores_param[] = $_GET['valor_filtro']; // O con comodines: "%" . $_GET['valor_filtro'] . "%"
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

    if ($result_lista && $result_lista->num_rows > 0) {
        while ($row = $result_lista->fetch_assoc()) {
            // Construir el HTML para cada fila
            // Asegúrate de que coincida exactamente con la estructura de la tabla en form_busc_regis_incidencia.php
            // y que los IDs para los modales de edición sean correctos si se van a seguir usando así.
            $output .= '<tr>';
            $output .= '<td>' . htmlspecialchars($row["nombre_cliente"]) . '</td>';
            $output .= '<td>' . htmlspecialchars($row["titulo"]) . '</td>';
            $output .= '<td>' . htmlspecialchars($row["descripcion"]) . '</td>';
            $output .= '<td>' . htmlspecialchars($row["nombre_prioridad"]) . '</td>';
            $output .= '<td>' . htmlspecialchars($row["nombre_estado"]) . '</td>';
            $output .= '<td>' . htmlspecialchars($row["fecha_inicio"]) . '</td>';
            $output .= '<td>' . htmlspecialchars($row["fecha_fin"]) . '</td>';
            $output .= '<td>';
            if (!empty($row['archivo'])) {
                $output .= '<a href="../php/uploads/' . rawurlencode($row['archivo']) . '" target="_blank">';
                $output .= '<i class="fas fa-file-alt"></i> Ver archivo';
                $output .= '</a>';
            } else {
                $output .= 'N/A';
            }
            $output .= '</td>';
            $output .= '<td class="acciones">';
            $output .= '<button class="btn-editar" onclick="abrirModal(\'modalEditar' . $row["id_incidencia"] . '\')"><i class="fas fa-edit"></i> Editar</button>';
            // El formulario de eliminar es más complejo de replicar aquí sin cambiar la lógica de eliminación.
            // Por simplicidad, podrías necesitar que la eliminación siga siendo una recarga de página,
            // o cambiar la eliminación para que también sea AJAX, lo cual está fuera del alcance actual.
            // Aquí solo se incluye el botón de editar para mantener la funcionalidad del modal.
            // Para el botón de eliminar:
            $output .= '<form method="get" action="/programacion/php/incidencias.php" onsubmit="return confirm(\'¿Eliminar esta incidencia?\')" style="display:inline;">';
            $output .= '<input type="hidden" name="eliminar" value="' . $row["id_incidencia"] . '">';
            $output .= '<button class="btn-eliminar" type="submit"><i class="fas fa-trash-alt"></i> Eliminar</button>';
            $output .= '</form>';
            $output .= '</td>';
            $output .= '</tr>';
        }
    } else {
        $output .= '<tr><td colspan="9" style="text-align:center;">No se encontraron incidencias que coincidan con los criterios de búsqueda.</td></tr>';
    }
    $stmt_lista->close();
} else {
    // Considerar devolver un error HTTP o un JSON de error
    $output .= '<tr><td colspan="9" style="text-align:center;">Error al preparar la consulta.</td></tr>';
}

$conn->close();

// Establecer el tipo de contenido a text/html porque estamos devolviendo marcado HTML
header('Content-Type: text/html; charset=utf-8');
echo $output;
?>
