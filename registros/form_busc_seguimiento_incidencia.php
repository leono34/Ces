
    <?php include '../php/incidencias.php'; ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Tabla de Personas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
       <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/estiloCliente.css">
    <link rel="stylesheet" href="../css/seguimiento.css">
    <link rel="stylesheet" href="../css/cliente.css">
  
</head>

<body>

    <h2>Seguimiento</h2>
    <div class="buscar-container">
            <div class="row g-2 mb-2">
                <div class="col-md-4">
                    <select  class="form-select" id="tipo_busqueda_seguimiento" aria-label="Tipo de búsqueda">
                        <option selected value="">Tipo de búsqueda</option>
                        <option value="dni_cliente">Por Dni Cliente</option>
                        <option value="prioridad_incidencia">Por Prioridad Incidencia</option>
                        <option value="estado_incidencia">Por Estado Incidencia</option>
                    </select>
                </div>
                <div class="col-md-8">
                    <input type="text" id="valor_busqueda_seguimiento" placeholder="Ingrese valor de búsqueda">
                </div>
            </div>
            <div class="d-grid gap-2 d-md-flex justify-content-md-start mb-3">
                <button  id="btnBuscarSeguimiento" class="btn btn-success fw-bold" type="button">BUSCAR</button>
                <button  id="btnLimpiarFiltroSeguimiento" class="btn btn-secondary fw-bold" type="button">Limpiar Filtros</button>
            </div>
    </div>
  
   <h2>Asignadas por mi</h2> <br>
    <table id="tablaSeguimientos">
        <thead>
            <tr>
                <th>Cliente</th>
                <th>titulo</th>
                <th>descripcion</th>
                <th>prioridad</th>
                <th>fecha_inicio</th>
                <th>fecha_fin</th>
                <th>Estado</th>
                <th>comentario</th>
                <th>dias</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                while ($row = $result_lista->fetch_assoc()): 
                        
                // Calcular diferencia de días entre fecha_inicio y fecha_fin
                $fecha_inicio = new DateTime($row["fecha_inicio"]);
                $fecha_fin = new DateTime($row["fecha_fin"]);
                $diferencia = $fecha_inicio->diff($fecha_fin);
                $dias = $diferencia->days; // número total de días
            ?>
            <tr>
                <td><?= $row["nombre_cliente"] ?></td>
                <td><?= $row["titulo"] ?></td>
                <td><?= $row["descripcion"] ?></td>
                <td><?= $row["nombre_prioridad"] ?></td>
                <td><?= $row["fecha_inicio"] ?></td>
                <td><?= $row["fecha_fin"] ?></td>
                <td><?= $row["nombre_estado"] ?></td>
                <td><?= $row["comentarios"] ?></td>
                <td><?= $dias?></td><!-- Mostramos los días calculados -->
                <td class="acciones">
                <button class="btn-ver" onclick="abrirModal('modalEditar<?= $row["id_incidencia" ] ?>')">
                    <i class="fas fa-eye"></i> Ver
                </button>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

     <?php while ($row = $result_total->fetch_assoc()): ?>
<div id="modalEditar<?= $row['id_incidencia'] ?>" class="modal">
    <div class="modal-contenido" style="max-width: 800px; width: 90%;">
        <span class="close" onclick="cerrarModal('modalEditar<?= $row['id_incidencia'] ?>')">&times;</span>
        <div class="modal-header">
            <h2>Comentarios y Estado</h2>
        </div>

        <!-- Timeline Container -->
        <div class="timeline-container">
            <!-- Estado Actual -->
            <div class="timeline-item">
                <div class="timeline-icon status-icon">
                    <i class="fas fa-info-circle"></i>
                </div>
                <div class="timeline-content">
                    <div class="timeline-header">
                        <span class="status-badge">Estado: <?= htmlspecialchars($row['nombre_estado']) ?></span>
                        <span class="timeline-date"><?= date('d/m/Y H:i', strtotime($row['fecha_inicio'])) ?> PM</span>
                    </div>
                    <div class="timeline-details">
                        <p><strong>Fecha ingreso de incidencia:</strong> <?= date('d/m/Y H:i', strtotime($row['fecha_inicio'])) ?> PM</p>
                        <p><strong>Registro original:</strong> <?= date('d/m/Y', strtotime($row['fecha_inicio'])) ?></p>
                        <p><strong>Tipo de solicitud:</strong> Incidencia</p>
                        <p><strong>Cliente:</strong> <?= htmlspecialchars($row['nombre_cliente']) ?></p>
                        <p><strong>Título:</strong> <?= htmlspecialchars($row['titulo']) ?></p>
                        <p><strong>Descripción:</strong> <?= htmlspecialchars($row['descripcion']) ?></p>
                        <p><strong>Prioridad:</strong> <?= htmlspecialchars($row['nombre_prioridad']) ?></p>
                        <p><strong>Fecha fin:</strong> <?= date('d/m/Y', strtotime($row['fecha_fin'])) ?></p>
                    </div>
                </div>
            </div>

            <!-- Comentarios Existentes -->
            <?php if (!empty($row['comentarios'])): ?>
            <div class="timeline-item">
                <div class="timeline-icon comment-icon">
                    <i class="fas fa-comment"></i>
                </div>
                <div class="timeline-content">
                    <div class="timeline-header">
                        <span class="comment-badge">Comentario existente</span>
                        <span class="timeline-date"><?= date('d/m/Y H:i', strtotime($row['fecha_fin'])) ?> PM</span>
                    </div>
                    <div class="timeline-details">
                        <p><?= htmlspecialchars($row['comentarios']) ?></p>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Formulario para agregar comentario -->
        <div class="add-comment-section">
            <h3>Agregar Comentario</h3>
            <form action="../php/seguimiento.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id_incidencia" value="<?= htmlspecialchars($row['id_incidencia']) ?>">
                <input type="hidden" name="id_cliente" value="<?= htmlspecialchars($row['id_cliente']) ?>">
                <input type="hidden" name="titulo" value="<?= htmlspecialchars($row['titulo']) ?>">
                <input type="hidden" name="descripcion" value="<?= htmlspecialchars($row['descripcion']) ?>">
                <input type="hidden" name="id_prioridad" value="<?= htmlspecialchars($row['id_prioridad']) ?>">
                <input type="hidden" name="fecha_inicio" value="<?= htmlspecialchars($row['fecha_inicio']) ?>">
                <input type="hidden" name="fecha_fin" value="<?= htmlspecialchars($row['fecha_fin']) ?>">

                <div class="form-group">
                    <label for="comentario_<?= $row['id_incidencia'] ?>">Nuevo Comentario:</label>
                    <textarea id="comentario_<?= $row['id_incidencia'] ?>" name="comentario" rows="4" cols="50" placeholder="Escriba su comentario aquí..." required></textarea>
                </div>

                <div class="form-group">
                    <label for="id_estado_<?= $row['id_incidencia'] ?>">Cambiar Estado:</label>
                    <select name="id_estado" id="id_estado_<?= $row['id_incidencia'] ?>" required>
                        <option value="">-- Seleccione Estado --</option>
                        <?php
                        $sql_estados = "SELECT id_estado, nombre_estado FROM estados_reclamos;";
                        $result_estados_modal = $conn->query($sql_estados);
                        if ($result_estados_modal && $result_estados_modal->num_rows > 0) {
                            while ($row_estado_modal = $result_estados_modal->fetch_assoc()) {
                                $selected_estado = ($row["id_estado"] == $row_estado_modal["id_estado"]) ? ' selected' : '';
                                echo '<option value="' . htmlspecialchars($row_estado_modal["id_estado"]) . '"' . $selected_estado . '>' .
                                     htmlspecialchars($row_estado_modal["nombre_estado"]) .
                                     '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-guardar" name="editar">
                        <i class="fas fa-save"></i> Guardar Comentario
                    </button>
                    <button type="button" class="btn-cerrar" onclick="cerrarModal('modalEditar<?= $row['id_incidencia'] ?>')">
                        <i class="fas fa-times"></i> Cerrar
                    </button>
                </div>
            </form>
            <!-- Historial de seguimiento -->
            <div class="historial-section">
                <h3>Historial de Seguimiento</h3>
                <?php
                $sqlHist = "SELECT estado_anterior, estado_nuevo, comentario, fecha_hora
                            FROM historial_seguimiento
                            WHERE id_incidencia = ?
                            ORDER BY fecha_hora ASC";
                $stmtHist = $conn->prepare($sqlHist);
                $stmtHist->bind_param("i", $row['id_incidencia']);
                $stmtHist->execute();
                $resultHist = $stmtHist->get_result();

                if ($resultHist->num_rows > 0) {
                    while ($rowHist = $resultHist->fetch_assoc()) {
                        echo "<div class='historial-comentario'>";
                        echo "<strong>" . htmlspecialchars($rowHist['fecha_hora']) . "</strong>: ";
                        echo "<em>" . htmlspecialchars($rowHist['estado_anterior']) . " ➜ " . htmlspecialchars($rowHist['estado_nuevo']) . "</em><br>";
                        echo nl2br(htmlspecialchars($rowHist['comentario']));
                        echo "</div><hr>";
                    }
                } else {
                    echo "<p>No hay historial aún.</p>";
                }
                $stmtHist->close();
                ?>
            </div>
        </div>
    </div>
</div>
<?php endwhile; ?>

<!-- Modal Insertar -->
<div id="modalInsertar" class="modal">
    <div class="modal-contenido">
        <span class="close" onclick="cerrarModal('modalInsertar')">&times;</span>
        <div class="modal-header">Nueva Incidencia</div>
        <form action="../registros/form_busc_regis_incidencia.php" method="post">
        <div class="form-group">    <input type="text" name="id_cliente" placeholder="id_cliente" required></div>
        <div class="form-group"> <input type="text" name="titulo" placeholder="titulo" required></div>
        <div class="form-group">  <input type="text" name="descripcion" placeholder="descripcion" required></div>
        <div class="form-group"> <input type="text" name="id_prioridad" placeholder="id_prioridad" maxlength="8" required></div>
        <div class="form-group"> <input type="text" name="fecha_inicio" placeholder="fecha_inicio" required></div>
        <div class="form-group"> <input type="text" name="fecha_fin" placeholder="fecha_fin" required></div>
        <div class="form-group"> <input name="archivo" placeholder="archivo" required></div>
            <div class="form-actions">
                <button type="submit" class="btn-guardar" name="insertar"><i class="fas fa-save"></i> Agregar</button>
            </div>
        </form>
    </div>
</div>

</body>