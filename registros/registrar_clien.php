<?php
// Simulación de conexión a base de datos y obtención de datos
// En un caso real, aquí iría la lógica para conectar a la BD
// y obtener los datos de los clientes/registros.

$datos = [
    ['id' => 1, 'dni' => '12345678A', 'nombre' => 'Juan Perez', 'prioridad' => 'alta', 'estado' => 'ingresado'],
    ['id' => 2, 'dni' => '87654321B', 'nombre' => 'Ana Lopez', 'prioridad' => 'media', 'estado' => 'en revision'],
    ['id' => 3, 'dni' => '11223344C', 'nombre' => 'Carlos Garcia', 'prioridad' => 'baja', 'estado' => 'finalizado'],
    ['id' => 4, 'dni' => '44556677D', 'nombre' => 'Laura Martin', 'prioridad' => 'alta', 'estado' => 'anulado'],
    ['id' => 5, 'dni' => '99887766E', 'nombre' => 'Pedro Sanchez', 'prioridad' => 'media', 'estado' => 'ingresado'],
];

// Lógica de filtrado (simplificada)
$dni_filtro = isset($_GET['dni']) ? $_GET['dni'] : '';
$prioridad_filtro = isset($_GET['prioridad']) ? $_GET['prioridad'] : '';
$estado_filtro = isset($_GET['estado']) ? $_GET['estado'] : '';

$datos_filtrados = $datos;

if (!empty($dni_filtro)) {
    $datos_filtrados = array_filter($datos_filtrados, function ($item) use ($dni_filtro) {
        return strpos($item['dni'], $dni_filtro) !== false;
    });
}

if (!empty($prioridad_filtro)) {
    $datos_filtrados = array_filter($datos_filtrados, function ($item) use ($prioridad_filtro) {
        return $item['prioridad'] === $prioridad_filtro;
    });
}

if (!empty($estado_filtro)) {
    $datos_filtrados = array_filter($datos_filtrados, function ($item) use ($estado_filtro) {
        return $item['estado'] === $estado_filtro;
    });
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registros de Clientes</title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        .filtros { margin-bottom: 20px; padding: 15px; border: 1px solid #ccc; border-radius: 5px; }
        .filtros label { margin-right: 10px; }
        .filtros input[type="text"], .filtros select { padding: 8px; margin-right: 15px; border: 1px solid #ddd; border-radius: 3px; }
        .filtros button { padding: 8px 15px; background-color: #007bff; color: white; border: none; border-radius: 3px; cursor: pointer; }
        .filtros button.limpiar { background-color: #6c757d; }
        .filtros button.pdf { background-color: #28a745; margin-left: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f8f9fa; }
    </style>
</head>
<body>

    <h1>Registros</h1>

    <div class="filtros">
        <form method="GET" action="">
            <label for="dni">DNI:</label>
            <input type="text" id="dni" name="dni" value="<?php echo htmlspecialchars($dni_filtro); ?>">

            <label for="prioridad">Prioridad:</label>
            <select id="prioridad" name="prioridad">
                <option value="">Todas</option>
                <option value="alta" <?php echo ($prioridad_filtro === 'alta') ? 'selected' : ''; ?>>Alta</option>
                <option value="media" <?php echo ($prioridad_filtro === 'media') ? 'selected' : ''; ?>>Media</option>
                <option value="baja" <?php echo ($prioridad_filtro === 'baja') ? 'selected' : ''; ?>>Baja</option>
            </select>

            <label for="estado">Estado:</label>
            <select id="estado" name="estado">
                <option value="">Todos</option>
                <option value="ingresado" <?php echo ($estado_filtro === 'ingresado') ? 'selected' : ''; ?>>Ingresado</option>
                <option value="en revision" <?php echo ($estado_filtro === 'en revision') ? 'selected' : ''; ?>>En Revisión</option>
                <option value="finalizado" <?php echo ($estado_filtro === 'finalizado') ? 'selected' : ''; ?>>Finalizado</option>
                <option value="anulado" <?php echo ($estado_filtro === 'anulado') ? 'selected' : ''; ?>>Anulado</option>
            </select>

            <button type="submit">Filtrar</button>
            <button type="button" class="limpiar" onclick="window.location.href='registrar_clien.php'">Limpiar Filtros</button>
            <button type="button" class="pdf" onclick="generarPDF()">Generar Reporte PDF</button>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>DNI</th>
                <th>Nombre</th>
                <th>Prioridad</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($datos_filtrados)): ?>
                <?php foreach ($datos_filtrados as $dato): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($dato['id']); ?></td>
                        <td><?php echo htmlspecialchars($dato['dni']); ?></td>
                        <td><?php echo htmlspecialchars($dato['nombre']); ?></td>
                        <td><?php echo htmlspecialchars(ucfirst($dato['prioridad'])); ?></td>
                        <td><?php echo htmlspecialchars(ucfirst($dato['estado'])); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No se encontraron registros con los filtros aplicados.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <script>
        function generarPDF() {
            // Aquí iría la lógica para generar el PDF.
            // Por ahora, solo mostraremos una alerta.
            // Se podrían tomar los datos de la tabla actual o rehacer la consulta
            // con los filtros aplicados para pasarlos a una librería de generación de PDF.
            alert("Función Generar PDF no implementada aún. Se generarían los datos filtrados.");

            // Ejemplo de cómo podrías recolectar los filtros actuales para enviar a un script PHP que genere el PDF:
            const dni = document.getElementById('dni').value;
            const prioridad = document.getElementById('prioridad').value;
            const estado = document.getElementById('estado').value;
            // window.open(`generar_reporte_pdf.php?dni=${dni}&prioridad=${prioridad}&estado=${estado}`, '_blank');
        }
    </script>

</body>
</html>
