<?php
include '../php/cliente.php';  // incluye las consultas y variables definidas
include '../php/incidencias.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/estiloCliente.css">
    <link rel="stylesheet" href="../css/incidencia.css">  
    <link rel="stylesheet" href="../css/cliente.css">

</head>

<body>
    <h2>Lista de Reportes</h2>
    <div class="buscar-container">
            <div class="row g-2 mb-2">
                <div class="col-md-4">
                    <select  class="form-select" id="tipo_busqueda_incidencia" aria-label="Tipo de búsqueda">
                        <option selected value="">Tipo de búsqueda</option>
                        <option value="dni_cliente">Por Dni Cliente</option>
                        <option value="prioridad">Por Prioridad</option>
                    </select>
                </div>
                <div class="col-md-8">
                    <input type="text" id="valor_busqueda" placeholder="Ingrese valor de búsqueda">
                </div>
            </div>
            <div class="d-grid gap-2 d-md-flex justify-content-md-start mb-3">
                <button  id="btnBuscarIncidencia" class="btn btn-success fw-bold" type="button">BUSCAR</button>
                <button  id="btnGenerarReportePDF" class="btn btn-danger fw-bold" type="button">GENERAR REPORTE PDF</button>
            </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Titulo</th>
                <th>Descripcion</th>
                <th>Prioridad</th>
                <th>Estado</th>
                <th>Fecha inicio</th>
                <th>Fecha fin</th>
                <th>Archivo</th>    
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result_lista->fetch_assoc()): ?>
            <tr>
                <td><?= $row["nombre_cliente"] ?></td>
                <td><?= $row["titulo"] ?></td>
                <td><?= $row["descripcion"] ?></td>
                <td><?= $row["nombre_prioridad"] ?></td>
                <td><?= htmlspecialchars($row["nombre_estado"]) ?></td>
                <td><?= $row["fecha_inicio"] ?></td>
                <td><?= $row["fecha_fin"] ?></td>
                 
                <td>
                    <a href="../php/uploads/<?= $row['archivo'] ?>" target="_blank">
                <i class="fas fa-file-alt"></i> Ver archivo
                </a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnGenerarReportePDF = document.getElementById('btnGenerarReportePDF');
    if (btnGenerarReportePDF) {
        btnGenerarReportePDF.addEventListener('click', function() {
            const tipoBusqueda = document.getElementById('tipo_busqueda_incidencia').value;
            const valorBusqueda = document.getElementById('valor_busqueda').value;

            let url = '../php/generar_reporte_incidencias_pdf.php';
            const params = new URLSearchParams();

            if (tipoBusqueda) {
                params.append('tipo_busqueda', tipoBusqueda);
            }
            if (valorBusqueda) {
                params.append('valor_busqueda', valorBusqueda);
            }

            if (params.toString()) {
                url += '?' + params.toString();
            }

            window.open(url, '_blank');
        });
    }

    // Script para la búsqueda existente (si es necesario adaptarlo o asegurarse de que no haya conflictos)
    const btnBuscarIncidencia = document.getElementById('btnBuscarIncidencia');
    if (btnBuscarIncidencia) {
        btnBuscarIncidencia.addEventListener('click', function() {
            const tipoBusqueda = document.getElementById('tipo_busqueda_incidencia').value;
            const valorBusqueda = document.getElementById('valor_busqueda').value;

            let queryParams = '';
            if (tipoBusqueda && valorBusqueda) {
                queryParams = `?tipo_busqueda_incidencia=${encodeURIComponent(tipoBusqueda)}&valor_busqueda_incidencia=${encodeURIComponent(valorBusqueda)}`;
            } else if (tipoBusqueda) {
            }

            // Actualiza la URL de la página actual para realizar la búsqueda
            window.location.href = `registrar_clien.php${queryParams}`;
        });
    }

    // Adjuntar eventos de cierre a todos los botones de cerrar modal
    var closeButtons = document.querySelectorAll('.modal .close');
    closeButtons.forEach(function(button) {
        button.onclick = function() {
            var modalId = button.closest('.modal').id;
            cerrarModal(modalId);
        }
    });
});
</script>
</body>