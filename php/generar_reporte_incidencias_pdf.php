<?php
// Incluir la librería FPDF
// Asegúrate de que la ruta sea correcta respecto a la ubicación de este script.
require('lib/fpdf/fpdf.php');

// Conexión a la base de datos
$host = "localhost";
$user = "root";
$password = "";
$database = "incidencias";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    // En un caso real, podrías generar un PDF de error o simplemente morir.
    die("Conexión fallida: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4"); // Es buena idea establecer el charset

// Obtener filtros GET
$tipo_filtro = $_GET['tipo_filtro'] ?? null;
$valor_filtro = $_GET['valor_filtro'] ?? null;
$filtro_aplicado_info = "Ninguno";

// Consulta SQL base
$sql_base = "SELECT i.id_incidencia,
             CONCAT_WS(' ', c.nombres, c.apellido_paterno, c.apellido_materno) AS nombre_cliente,
             i.titulo, i.descripcion AS desc_incidencia,
             p.descripcion AS nombre_prioridad,
             e.nombre_estado,
             i.fecha_inicio, i.fecha_fin
             FROM incidencia i
             JOIN clientes c ON i.id_cliente = c.id_cliente
             JOIN prioridad p ON i.id_prioridad = p.id_prioridad
             JOIN estados_reclamos e ON i.id_estado = e.id_estado";

$condiciones = [];
$tipos_param = "";
$valores_param = [];

if ($tipo_filtro && $valor_filtro) {
    switch ($tipo_filtro) {
        case 'dni_cliente':
            $condiciones[] = "c.dni = ?";
            $tipos_param .= "s";
            $valores_param[] = $valor_filtro;
            $filtro_aplicado_info = "DNI Cliente: " . htmlspecialchars($valor_filtro);
            break;
        case 'prioridad': // Asumiendo que 'prioridad' es el value usado en form_busc_regis_incidencia
            $condiciones[] = "p.descripcion LIKE ?";
            $tipos_param .= "s";
            $valores_param[] = $valor_filtro;
            $filtro_aplicado_info = "Prioridad: " . htmlspecialchars($valor_filtro);
            break;
        case 'estado_incidencia': // Si se implementa filtro por estado en UI de incidencias
             $condiciones[] = "e.nombre_estado LIKE ?";
             $tipos_param .= "s";
             $valores_param[] = $valor_filtro;
             $filtro_aplicado_info = "Estado: " . htmlspecialchars($valor_filtro);
            break;
    }
} elseif ($tipo_filtro && !$valor_filtro) {
    // Si se pasa un tipo de filtro pero no un valor, y el filtro requiere un valor,
    // decidimos no aplicar el filtro para el reporte para evitar errores o resultados inesperados.
    // O podrías decidir generar un error o un reporte vacío con un mensaje.
    // Por ahora, se listarán todos si el valor falta para un filtro que lo necesita.
    $filtro_aplicado_info = "Filtro '".htmlspecialchars($tipo_filtro)."' sin valor especificado - mostrando todos.";
}


if (!empty($condiciones)) {
    $sql_base .= " WHERE " . implode(" AND ", $condiciones);
}
$sql_base .= " ORDER BY i.id_incidencia ASC";

$stmt_lista = $conn->prepare($sql_base);

if ($stmt_lista) {
    if (!empty($valores_param)) {
        $stmt_lista->bind_param($tipos_param, ...$valores_param);
    }
    $stmt_lista->execute();
    $result_lista = $stmt_lista->get_result();
} else {
    die("Error al preparar la consulta: " . $conn->error);
}

// --- Inicio de la generación del PDF ---
class PDF extends FPDF {
    // Cabecera de página
    function Header() {
        // Logo (opcional)
        // $this->Image('logo.png',10,6,30);
        $this->SetFont('Arial','B',15);
        $this->Cell(0,10,utf8_decode('Reporte de Incidencias'),0,1,'C');
        $this->Ln(5); // Salto de línea
    }

    // Pie de página
    function Footer() {
        $this->SetY(-15); // Posición a 1.5 cm del final
        $this->SetFont('Arial','I',8);
        // Número de página usando el alias configurado
        $this->Cell(0,10,utf8_decode('Página ').$this->PageNo().'/{nb}',0,0,'C');
    }

    // Tabla de datos
    function FancyTable($header, $data) {
        // Colores, ancho de línea y fuente en negrita para cabeceras
        $this->SetFillColor(200,220,255); // Un azul claro
        $this->SetTextColor(0);
        $this->SetDrawColor(128,0,0);
        $this->SetLineWidth(.3);
        $this->SetFont('','B', 8); // Tamaño de fuente más pequeño para cabeceras y datos

        // Cabecera
        // Anchos de las columnas (ajustar según necesidad y orientación)
        // Total para apaisado A4 (aprox 297mm - 20mm margenes = 277mm)
        $w = array(45, 45, 70, 25, 25, 25, 25); // Cliente, Titulo, Desc, Prioridad, Estado, F. Inicio, F. Fin
        for($i=0;$i<count($header);$i++)
            $this->Cell($w[$i],7,utf8_decode($header[$i]),1,0,'C',true);
        $this->Ln();

        // Restauración de colores y fuentes para los datos
        $this->SetFillColor(224,235,255);
        $this->SetTextColor(0);
        $this->SetFont('', '', 7); // Tamaño de fuente aún más pequeño para datos

        // Datos
        $fill = false;
        foreach($data as $row) {
            $this->Cell($w[0],6,utf8_decode($row['nombre_cliente']),'LR',0,'L',$fill);
            $this->Cell($w[1],6,utf8_decode($row['titulo']),'LR',0,'L',$fill);
            // Para descripción, usar MultiCell si puede ser muy larga, o truncar.
            // Aquí se usa Cell, podría truncarse visualmente si es muy larga.
            // $this->MultiCell($w[2],6,utf8_decode($row['desc_incidencia']),'LR','L',$fill);
            // Para MultiCell, el manejo de la altura de fila y el salto de celda es diferente.
            // Por simplicidad, usamos Cell.
            $this->Cell($w[2],6,utf8_decode(substr($row['desc_incidencia'], 0, 55)),'LR',0,'L',$fill); // Truncar descripción
            $this->Cell($w[3],6,utf8_decode($row['nombre_prioridad']),'LR',0,'L',$fill);
            $this->Cell($w[4],6,utf8_decode($row['nombre_estado']),'LR',0,'L',$fill);
            $this->Cell($w[5],6,utf8_decode($row['fecha_inicio']),'LR',0,'C',$fill);
            $this->Cell($w[6],6,utf8_decode($row['fecha_fin']),'LR',0,'C',$fill);
            $this->Ln();
            $fill = !$fill;
        }
        // Línea de cierre
        $this->Cell(array_sum($w),0,'','T');
    }
}

// Creación del objeto PDF
$pdf = new PDF('L','mm','A4'); // L para Landscape (apaisado)
$pdf->AliasNbPages(); // Define el alias '{nb}' para el número total de páginas
$pdf->AddPage();
$pdf->SetFont('Arial','',10);

// Mostrar información del filtro aplicado
$pdf->Cell(0,7,utf8_decode("Filtros Aplicados: " . $filtro_aplicado_info),0,1);
$pdf->Ln(2);

// Cabeceras de la tabla
$header = array('Cliente', 'Titulo', 'Descripcion', 'Prioridad', 'Estado', 'F. Inicio', 'F. Fin');
$data_for_pdf = array();

if ($result_lista) {
    while ($row = $result_lista->fetch_assoc()) {
        $data_for_pdf[] = $row;
    }
    $stmt_lista->close();
}
$conn->close();

if (count($data_for_pdf) > 0) {
    $pdf->FancyTable($header, $data_for_pdf);
} else {
    $pdf->Cell(0,10,utf8_decode('No se encontraron incidencias con los filtros aplicados.'),0,1);
}

$pdf->Output('D', 'reporte_incidencias.pdf');
exit;
?>
