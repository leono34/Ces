<?php
include '../php/cliente.php';  // incluye las consultas y variables definidas
include '../php/incidencias.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Tabla de Personas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/estiloCliente.css">
    <link rel="stylesheet" href="../css/incidencias.css">  
    <link rel="stylesheet" href="../css/cliente.css">

</head>

<body>
    <h2>Lista de Incidencias</h2>
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
                <button  id="btnLimpiarFiltroIncidencias" class="btn btn-secondary fw-bold" type="button">Limpiar Filtros</button>
            </div>
    </div>

    
  <button class="btn-nuevo" onclick="abrirModal('modalInsertar')"> <i class="fas fa-plus"></i> Nueva Incidencia</button>
    <table id="tablaIncidencias">
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
                <th>Acciones</th>
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
                <td class="acciones">

                    <button class="btn-editar" onclick="abrirModal('modalEditar<?= $row["id_incidencia"] ?>')"><i class="fas fa-edit"></i> Editar</button>           
   

                    <form method="get" action="/programacion/php/incidencias.php" onsubmit="return confirm('¿Eliminar esta incidencia?')">
                        <input type="hidden" name="eliminar" value="<?= $row["id_incidencia"] ?>">
                        <button class="btn-eliminar" type="submit">
                            <i class="fas fa-trash-alt"></i> Eliminar
                        </button>
                    </form>

                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- EDITAR MODAL -->
     <!-- EDITAR MODAL -->
      <!-- EDITAR MODAL -->
<!-- EDITAR MODAL -->

    <?php while ($row = $result_lista_2->fetch_assoc()): ?>
    <div id="modalEditar<?= $row['id_incidencia'] ?>" class="modal">
        <div class="modal-contenido">
            <span class="close" onclick="cerrarModal('modalEditar<?= $row['id_incidencia'] ?>')">&times;</span>
            <div class="modal-header"><h2>Editar Seguimiento</h2></div>

            <!-- FORMULARIO -->
            <form action="../php/incidencias.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id_incidencia" value="<?= htmlspecialchars($row['id_incidencia']) ?>">

            
                    <div class="form-group">
                         <label for="">Cliente:</label>
                <select name="id_cliente" id="id_cliente">
                <option value="">-- Seleccione --</option>
                <?php
                // Consulta
                $sql = "SELECT id_cliente, CONCAT_WS(' ', nombres, apellido_paterno, apellido_materno) AS dato
                FROM clientes;";
                $result_cliente2 = $conn->query($sql);
                if ($result_cliente2->num_rows > 0) {
                // Salida de cada fila
                while ($row2 = $result_cliente2->fetch_assoc()) {
              
                        $selected = (  $row["id_cliente"]==$row2["id_cliente"]) ? ' selected' : '';
                        echo '<option value="' . $row2["id_cliente"] . '"' . $selected . '>' . 
                        htmlspecialchars($row2["dato"]) . 
                        '</option>';
                }
          } else {
              echo '<option value="">No hay clientes</option>';
          } ?>
            </select></div>
                <div class="form-group">
                    <label>Titulo:</label>
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
                $sql = "SELECT id_prioridad, descripcion FROM prioridad;";
                $result_prioridad = $conn->query($sql);
                if ($result_prioridad->num_rows > 0) {
              // Salida de cada fila
              while ($rowPrioridad = $result_prioridad->fetch_assoc()) {
              
                        $selected = (  $row["id_prioridad"]==$rowPrioridad["id_prioridad"]) ? ' selected' : '';
                        echo '<option value="' . $rowPrioridad["id_prioridad"] . '"' . $selected . '>' . 
                        htmlspecialchars($rowPrioridad["descripcion"]) . 
                        '</option>';

              }
          } else {
              echo '<option value="">No hay prioridades</option>'; // Corregido el mensaje
          } ?>
            </select></div>

                <div class="form-group">
                    <label for="id_estado_<?= $row['id_incidencia'] ?>">Estado:</label>
                    <select name="id_estado" id="id_estado_<?= $row['id_incidencia'] ?>">
                        <option value="">-- Seleccione Estado --</option>
                        <?php
                        // Consulta para obtener todos los estados
                        $sql_estados = "SELECT id_estado, nombre_estado FROM estados_reclamos;";
                        $result_estados_modal = $conn->query($sql_estados);
                        if ($result_estados_modal && $result_estados_modal->num_rows > 0) {
                            while ($row_estado_modal = $result_estados_modal->fetch_assoc()) {
                                $selected_estado = ($row["id_estado"] == $row_estado_modal["id_estado"]) ? ' selected' : '';
                                echo '<option value="' . htmlspecialchars($row_estado_modal["id_estado"]) . '"' . $selected_estado . '>' .
                                     htmlspecialchars($row_estado_modal["nombre_estado"]) .
                                     '</option>';
                            }
                        } else {
                            echo '<option value="">No hay estados disponibles</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Fecha Inicio:</label>
                    <input type="date" name="fecha_inicio" value="<?= htmlspecialchars($row['fecha_inicio']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Fecha Fin:</label>
                    <input type="date" name="fecha_fin" value="<?= htmlspecialchars($row['fecha_fin']) ?>" required>
                </div>
                <div class="form-group">
                   <label>Archivo:</label>
                <input type="file" id="archivo" name="archivo" accept="application/pdf">
                </div>   
                   <div class="form-group">
                     <label></label>
                <p> <?= $row['archivo'] ?></p>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-guardar" name="editar">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
    <?php endwhile; ?>

<!-- --------------------------------------------------------------------------------------- -->
<!-- Modal Insertar -->
<div id="modalInsertar" class="modal">
    <div class="modal-contenido">
        <span class="close" onclick="cerrarModal('modalInsertar')">&times;</span>
        <div class="modal-header"><h2>Nueva Incidencia</h2></div>
        <form action="../php/incidencias.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>Cliente:</label>
      <select name="id_cliente" id="id_cliente">
                <option value="">-- Seleccione --</option>
                <?php if ($result_cliente->num_rows > 0) {
              // Salida de cada fila
              while ($row = $result_cliente->fetch_assoc()) {
                  echo '<option value="' .
                      $row["id_cliente"] .
                      '">' .
                      htmlspecialchars($row["dato"]) .
                      "</option>";
              }
          } else {
              echo '<option value="">No hay empresas</option>';
          } ?>
            </select></div>
        <div class="form-group"> <label>Titulo:</label><input type="text" name="titulo" placeholder="titulo" required></div>
        <div class="form-group"> <label>Descripción:</label> <input type="text" name="descripcion" placeholder="descripcion" required></div>
         <div class="form-group">
                         <label for="">Prioridad:</label>
      <select name="id_prioridad" id="id_prioridad">
                <option value="">-- Seleccione --</option>
                <?php
                // Consulta
                $sql = "SELECT id_prioridad, descripcion FROM prioridad;";
                $result_prioridad = $conn->query($sql);
                if ($result_prioridad->num_rows > 0) {
              // Salida de cada fila
              while ($rowPrioridad = $result_prioridad->fetch_assoc()) {
              
                        $selected = (  $row["id_prioridad"]==$rowPrioridad["id_prioridad"]) ? ' selected' : '';
                echo '<option value="' . $rowPrioridad["id_prioridad"] . '"' . $selected . '>' . 
                    htmlspecialchars($rowPrioridad["descripcion"]) . 
                    '</option>';
              }
          } else {
              echo '<option value="">No hay estados</option>';
          } ?>
            </select></div>
             <div  class="form-group"> <label>Archivo:</label> <input type="file" id="archivo" name="archivo" accept="application/pdf"></div>
        <div class="form-group"> <label>Fecha Inicio:</label><input type="date" name="fecha_inicio" placeholder="fecha_inicio" required></div>
        <div class="form-group"><label>Fecha Fin:</label> <input type="date" name="fecha_fin" placeholder="fecha_fin" required></div>
       
            <div class="form-actions">
                <button type="submit" class="btn-guardar" name="insertar"><i class="fas fa-save"></i> Agregar</button>
            </div>
        </form>
    </div>
</div>

</body>