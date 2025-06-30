<?php
// Conexión a la base de datos
$host = "localhost";
$user = "root";
$password = "";
$database = "incidencias";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    // En un entorno de producción, sería mejor loguear el error y devolver un mensaje genérico o un error HTTP.
    die("Conexión fallida: " . $conn->connect_error);
}

$output = ''; 

$sql_base = "SELECT i.*, e.nombre_estado,
             CONCAT_WS(' ', c.nombres, c.apellido_paterno, c.apellido_materno) AS nombre_cliente,
             p.descripcion AS nombre_prioridad
             FROM incidencia i
             JOIN clientes c ON i.id_cliente = c.id_cliente
             JOIN prioridad p ON i.id_prioridad = p.id_prioridad
             JOIN estados_reclamos e ON i.id_estado = e.id_estado";

$condiciones = [];
$tipos_param = "";
$valores_param = [];

// Se espera que los parámetros vengan por GET desde la petición fetch
if (isset($_GET['tipo_filtro']) && !empty($_GET['tipo_filtro']) && isset($_GET['valor_filtro']) && !empty($_GET['valor_filtro'])) {
    $tipo_filtro = $_GET['tipo_filtro'];
    $valor_filtro = $_GET['valor_filtro'];

    if ($tipo_filtro === 'dni_cliente') {
        $condiciones[] = "c.dni = ?";
        $tipos_param .= "s";
        $valores_param[] = $valor_filtro;
    } elseif ($tipo_filtro === 'prioridad_incidencia') {
        $condiciones[] = "p.descripcion LIKE ?";
        $tipos_param .= "s";
        $valores_param[] = $valor_filtro;
    } elseif ($tipo_filtro === 'estado_incidencia') {
        $condiciones[] = "e.nombre_estado LIKE ?";
        $tipos_param .= "s";
        $valores_param[] = $valor_filtro;
    }
}
// Si no hay tipo_filtro o valor_filtro, se listarán todos (o los que cumplan otras condiciones base si las hubiera).

if (!empty($condiciones)) {
    $sql_base .= " WHERE " . implode(" AND ", $condiciones);
}

$sql_base .= " ORDER BY i.id_incidencia"; // O el orden que prefieras para seguimientos

$stmt_lista = $conn->prepare($sql_base);

if ($stmt_lista) {
    if (!empty($valores_param)) {
        $stmt_lista->bind_param($tipos_param, ...$valores_param);
    }
    $stmt_lista->execute();
    $result_lista = $stmt_lista->get_result();

    if ($result_lista && $result_lista->num_rows > 0) {
        while ($row = $result_lista->fetch_assoc()) {
            // Calcular diferencia de días
            $fecha_inicio_dt = new DateTime($row["fecha_inicio"]);
            $fecha_fin_dt = new DateTime($row["fecha_fin"]);
            $diferencia = $fecha_inicio_dt->diff($fecha_fin_dt);
            $dias = $diferencia->days;

            $output .= '<tr>';
            $output .= '<td>' . htmlspecialchars($row["nombre_cliente"]) . '</td>';
            $output .= '<td>' . htmlspecialchars($row["titulo"]) . '</td>';
            $output .= '<td>' . htmlspecialchars($row["descripcion"]) . '</td>';
            $output .= '<td>' . htmlspecialchars($row["nombre_prioridad"]) . '</td>';
            $output .= '<td>' . htmlspecialchars($row["fecha_inicio"]) . '</td>';
            $output .= '<td>' . htmlspecialchars($row["fecha_fin"]) . '</td>';
            $output .= '<td>' . htmlspecialchars($row["nombre_estado"]) . '</td>';
            // Asumiendo que 'comentarios' es un campo en la tabla 'incidencia'
            $output .= '<td>' . htmlspecialchars($row["comentarios"] ?? '') . '</td>'; // Usar ?? '' por si es NULL
            $output .= '<td>' . $dias . '</td>';

            $output .= '<td class="acciones">';
            // El botón "Ver" abre el modal que permite editar comentarios y estado.
            $output .= '<button class="btn-ver" onclick="abrirModal(\'modalEditar' . $row["id_incidencia"] . '\')">';
            $output .= '<i class="fas fa-eye"></i> Ver';
            $output .= '</button>';
            // No se incluyen otros botones como editar/eliminar incidencia aquí, solo el "Ver" que lleva al modal de seguimiento.
            $output .= '</td>';
            $output .= '</tr>';
        }
    } else {
        $output .= '<tr><td colspan="11" style="text-align:center;">No se encontraron seguimientos que coincidan con los criterios de búsqueda.</td></tr>';
    }
    $stmt_lista->close();
} else {
    $error_message = $conn->error; // Captura el error específico de MySQL si es posible
    // En un entorno de producción, loguear $error_message.
    $output .= '<tr><td colspan="11" style="text-align:center;">Error al preparar la consulta.</td></tr>';
}

$conn->close();

header('Content-Type: text/html; charset=utf-8');
echo $output;
?>
