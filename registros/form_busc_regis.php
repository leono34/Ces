<?php
include '../php/cliente.php';  // incluye las consultas y variables definidas
?>
<head>
    <meta charset="UTF-8">
    <title>Tabla de Personas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/estiloCliente.css">
    <link rel="stylesheet" href="../css/cliente.css">

</head>

<body>
        <h2>Búsqueda e Ingreso de Datos</h2>
    <div class="buscar-container">       
            <div class="row g-2 mb-2">
                <div class="col-md-4">
                    <select  class="form-select" id="tipo_busqueda" aria-label="Tipo de búsqueda">
                        <option selected>Tipo de búsqueda</option>
                        <option value="dni">Por Dni</option>
                        <option value="correo">Por Correo</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="text" id="valor_busqueda" placeholder="Ingrese valor de búsqueda">
                </div>
                <div class="col-md-4 d-grid">
                    <button class="btn  fw-bold cliente fw-bold" type="button" onclick="abrirModal('modalVerClientes')"> VER CLIENTES</button>
                </div>
            </div>
            <div class="d-grid mb-3">
                <button  id="btnBuscar" class="btn btn-success fw-bold" type="button">BUSCAR</button>
            </div>
         <form action="../registros/form_busc_regis.php" method="POST">
            <div class="row g-2 mb-2">
                <div class="col-md-4">
                    <input type="text" id="nombres" name="nombres" class="form-control" placeholder="Nombres"  >
                </div>
                <div class="col-md-4">
                    <input type="text" id="apellido_paterno" name="apellido_paterno" class="form-control" placeholder="Apellido Paterno"  >
                </div>
                <div class="col-md-4">
                    <input type="text" id="apellido_materno" name="apellido_materno" class="form-control" placeholder="Apellido Materno"  >
                </div>
            </div>

            <div class="row g-2 mb-2">
                <div class="col-md-4">
                    <input type="text" id="dni" name="dni" class="form-control" placeholder="DNI" >
                </div>
                <div class="col-md-4">
                    <input type="email" id="correo" name="correo" class="form-control" placeholder="Correo Electrónico"  >
                </div>
                <div class="col-md-4">
                    <input type="text" id="telefono" name="telefono"  class="form-control" placeholder="Teléfono"  >
                </div>
            </div>

            <div class="row g-2 mb-3">
                <div class="col-md-5">
                    <input type="text"  id="direccion" name="direccion" class="form-control" placeholder="Dirección"  >
                </div>
                <div class="col-md-7">
                    <select class="form-select" name="id_empresa" id="id_empresa">
                        <option value="">-- Seleccione --</option>
                            <?php while($row2 = $result_empresas->fetch_assoc()): ?>
                            <option value="<?= $row2['id_empresa'] ?>"><?= htmlspecialchars($row2['nombre']) ?></option>
                            <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-2 d-grid">
                    <button type="submit" class="btn-nuevo" name="insertar" class="btn btn-success fw-bold"><i class="fas fa-plus"></i> AGREGAR </button>
                </div>
            </div>
        </form>
    </div>


<!-- editar para mostrar los datos -->
<!-- editar para mostrar los datos -->

    <?php while ($row = $result_lista2->fetch_assoc()): ?>
    <div id="modalEditar<?= $row['id_cliente'] ?>" class="modal">
        <div class="modal-contenido">
            <span class="close" onclick="cerrarModal('modalEditar<?= $row['id_cliente'] ?>')">&times;</span>
            <div class="modal-header"><h2>Editar Cliente</h2></div>

            <!-- FORMULARIO -->
            <form action="../registros/form_busc_regis.php" method="post">
                <input type="hidden" name="id_cliente" value="<?= htmlspecialchars($row['id_cliente']) ?>">

                <div class="form-group">
                    <label>Nombres:</label>
                    <input type="text" name="nombres" value="<?= htmlspecialchars($row['nombres']) ?>" required>
                </div>
                <div class="form-group"><label  >Apellido Paterno:</label>

                    <input type="text" name="apellido_paterno" value="<?= htmlspecialchars($row['apellido_paterno']) ?>"
                        required>
                </div>
                <div class="form-group">
                    <label  >Apellido Materno:</label>
                    <input type="text" name="apellido_materno" value="<?= htmlspecialchars($row['apellido_materno']) ?>"
                        required>
                </div>
                <div class="form-group">
                    <label  >DNI:</label>
                    <input type="text" name="dni" value="<?= htmlspecialchars($row['dni']) ?>" required>
                </div>
                <div class="form-group">
                        <label  >Correo:</label>
                    <input type="email" name="correo" value="<?= htmlspecialchars($row['correo']) ?>" required>
                </div>
                <div class="form-group">
                    <label  >Teléfono:</label>
                    <input type="text" name="telefono" value="<?= htmlspecialchars($row['telefono']) ?>" required>
                </div>
                <div class="form-group">
                    <label  >Dirección:</label>
                    <input type="text" name="direccion" value="<?= htmlspecialchars($row['direccion']) ?>" required>
                </div>
               
                <div class="form-group">
                         <label  >Empresa:</label>
                        <select name="id_empresa" id="id_empresa">
                            <option value="">-- Seleccione --</option>
                    
                            <?php
                                                             // Consulta
                            $sql = "SELECT id_empresa, nombre FROM  empresas;";
                            $result_empresas = $conn->query($sql);
                                if ($result_empresas->num_rows > 0) {
                            // Salida de cada fila
                            while ($row2 = $result_empresas->fetch_assoc()) {
                            
                                        $selected = (  $row["id_empresa"]==$row2["id_empresa"]) ? ' selected' : '';
                                        echo '<option value="' . $row2["id_empresa"] . '"' . $selected . '>' . 
                                            htmlspecialchars($row2["nombre"]) . 
                                            '</option>';
                                            }
                                        } else {
                                            echo '<option value="">No hay empresas</option>';
                                       } ?> 
                        </select>
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

<!-- Modal para ver clienres en tabla  -->

 <div id="modalVerClientes" class="modaltablagrande" style="display:none;">
        <div class="modaltablacontenido ">
        <span class="close" onclick="cerrarModal('modalVerClientes')">&times;</span>
        <div class="modal-header"><h2>Lista de Clientes</h2></div>
        <table>
        <thead>
            <tr>
                <th>Nombres</th>
                <th>Apellido Paterno</th>
                <th>Apellido Materno</th>
                <th>DNI</th>
                <th>Correo</th>
                <th>Teléfono</th>
                <th>Dirección</th>
                <th>Empresa</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($clientes as $row): ?>
            <tr>
                <td><?= $row["nombres"] ?></td>
                <td><?= $row["apellido_paterno"] ?></td>
                <td><?= $row["apellido_materno"] ?></td>
                <td><?= $row["dni"] ?></td>
                <td><?= $row["correo"] ?></td>
                <td><?= $row["telefono"] ?></td>
                <td><?= $row["direccion"] ?></td>
                <td><?= $row["nombre_empresa"] ?></td>
                <td class="acciones">

                    <button class="btn-editar" onclick="abrirModal('modalEditar<?= $row["id_cliente"] ?>')"><i class="fas fa-edit"></i>Editar</button>
                    <form method="get" action="/programacion/login/index.php" onsubmit="return confirm('¿Eliminar este cliente?')">
                        <input type="hidden" name="eliminar" value="<?= $row["id_cliente"] ?>">
                        <button class="btn-eliminar" type="submit"><i class="fas fa-trash-alt"></i> Eliminar</button>
                    </form>

                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</div>



<script>
    window.onclick = function(event) {
        const modales = document.querySelectorAll(".modal");
        modales.forEach(modal => {
            if (event.target === modal) {
                modal.style.display = "none";
            }
        });
    };
</script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>