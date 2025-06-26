
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
 <!-- <button class="btn-nuevo" onclick="abrirModal('modalInsertar')"> <i class="fas fa-plus"></i> Nueva Incidencia</button>-->
    <table id="tablaSeguimientos">
        <thead>
            <tr>
                <th>Cliente</th>
                <th>titulo</th>
                <th>descripcion</th>
                <th>prioridad</th>
                <th>fecha_inicio</th>
                <th>fecha_fin</th>
                <th>archivo</th>
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
                <td><?= $row["archivo"] ?></td>
                <td><?= $row["nombre_estado"] ?></td>
                <td><?= $row["comentarios"] ?></td>
                <td><?= $dias?></td><!-- Mostramos los días calculados -->
                <td class="acciones">
                <button class="btn-ver" onclick="abrirModal('modalEditar<?= $row[
                                    "id_incidencia"
                                ] ?>')">
                    <i class="fas fa-eye"></i> Ver
                </button>
                    <!--<button class="btn-editar" onclick="abrirModal('modalEditar<?= $row[
                    "id_incidencia"
                ] ?>')"><i class="fas fa-edit"></i>Editar</button>
                    <a href="/phpmyadmin/PROGRAMACION/php/incidencias.php?eliminar=<?= $row[
                    "id_incidencia"
                ] ?>" onclick="return confirm('¿Eliminar esta incidencia?')">
                        <button class="btn-eliminar"><i class="fas fa-trash-alt"></i> Eliminar</button>
                    </a>-->

                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <?php while ($row = $result_lista_2->fetch_assoc()): ?>
    <div id="modalEditar<?= $row['id_incidencia'] ?>" class="modal">
        <div class="modal-contenido">
            <span class="close" onclick="cerrarModal('modalEditar<?= $row['id_incidencia'] ?>')">&times;</span>
            <div class="modal-header"><h2>Ver Seguimiento</h2></div>

            <!-- FORMULARIO -->
            <form action="../php/seguimiento.php" method="post" enctype="multipart/form-data>">
                <input type="hidden" name="id_incidencia" value="<?= htmlspecialchars($row['id_incidencia']) ?>">
 <div class="form-group">
<label for="comentario">Comentario:</label><br>
<textarea id="comentario" name="comentario" rows="5" cols="40" placeholder="Escribe tu comentario aquí..."><?= htmlspecialchars($row['comentarios']) ?></textarea>


</div>



           <div class="form-group">
                         <label for="">Estado:</label>
      <select name="id_estado" id="id_estado">
                <option value="">-- Seleccione --</option>
                <?php
                // Consulta
$sql = "SELECT 
    id_estado, 
    nombre_estado
FROM 
    estados_reclamos;";
$result_reclamos = $conn->query($sql);
                if ($result_reclamos->num_rows > 0) {
              // Salida de cada fila
              while ($rowReclamo = $result_reclamos->fetch_assoc()) {
                        $selected = (  $row["id_estado"]==$rowReclamo["id_estado"]) ? ' selected' : '';
                        echo '<option value="' . $rowReclamo["id_estado"] . '"' . $selected . '>' .
                        htmlspecialchars($rowReclamo["nombre_estado"]) .
                        '</option>';
              }
          } else {
              echo '<option value="">No hay estados</option>';
          } ?>
            </select></div>
                
                            <div class="form-group">
                         <label for="">Cliente:</label>
      <select name="id_cliente" id="id_cliente">
                <option value="">-- Seleccione --</option>
                <?php
                // Consulta
$sql = "SELECT 
    id_cliente, 
    CONCAT_WS(' ', nombres, apellido_paterno, apellido_materno) AS dato
FROM 
    clientes;";
$result_cliente2 = $conn->query($sql);
                if ($result_cliente2->num_rows > 0) {
              // Salida de cada fila
              while ($row2 = $result_cliente2->fetch_assoc()) {
                        $selected = ($row["id_cliente"] == $row2["id_cliente"]) ? ' selected' : '';
                        echo '<option value="' . $row2["id_cliente"] . '"' . $selected . '>' . 
                            htmlspecialchars($row2["dato"]) . 
                            '</option>';
                    }
          } else {
              echo '<option value="">No hay clientes</option>';
          } ?>
            </select></div>
                <div class="form-group">
                    <label >Titulo:</label>
                    <input type="text" name="titulo" value="<?= htmlspecialchars($row['titulo']) ?>"
                        required>
                </div>
                <div class="form-group">
                    <label>Descripción:</label>
                    <input type="text" name="descripcion" value="<?= htmlspecialchars($row['descripcion']) ?>"
                        required>
                </div>
          
                <div class="form-group">
                         <label for="">Prioridad:</label>
      <select name="id_prioridad" id="id_prioridad">
                <option value="">-- Seleccione --</option>
                <?php
                // Consulta
$sql = "SELECT 
    id_prioridad, 
    descripcion
FROM 
    prioridad;";
$result_prioridad = $conn->query($sql);
                if ($result_prioridad->num_rows > 0) {
              // Salida de cada fila
                    while ($rowPrioridad = $result_prioridad->fetch_assoc()) {
                        $selected = ($row["id_prioridad"] == $rowPrioridad["id_prioridad"]) ? ' selected' : '';
                        echo '<option value="' . $rowPrioridad["id_prioridad"] . '"' . $selected . '>' . 
                            htmlspecialchars($rowPrioridad["descripcion"]) . 
                            '</option>';
                    }
          } else {
              echo '<option value="">No hay estados</option>';
          } ?>
            </select></div>
                <div class="form-group">
                    
                    <label >Fecha Inicio:</label>
                    <input type="text" name="fecha_inicio" value="<?= htmlspecialchars($row['fecha_inicio']) ?>" required>
                </div>
                <div class="form-group">
                    <label >Fecha Fin:</label>
                    <input type="text" name="fecha_fin" value="<?= htmlspecialchars($row['fecha_fin']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Archivo actual: <?= htmlspecialchars($row['archivo']) ?></label><br>
                    <input type="file" name="archivo">
                </div>


                <div class="form-actions">
                    <button type="submit" class="btn-guardar" name="editar">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                    <button onclick="cerrarModal('modalEditar<?= $row['id_incidencia'] ?>')" type="button" class="btn-cerrar" name="cerrar">
    <i class="fas fa-times"></i> Cerrar
</button>
                </div>
            </form>
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