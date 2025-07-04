
document.addEventListener('DOMContentLoaded', function () {
    
        const toggleMenu = document.querySelector('.toggle');
        const contenedor = document.querySelector('.contenedor');
        const main = document.querySelector('.main');

        toggleMenu.addEventListener('click', () => {
        contenedor.classList.toggle('oculto');
        main.classList.toggle('expandido');
        });

    const toggles = document.querySelectorAll('.toggle-submenu');
    toggles.forEach(function(toggle) {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            const parent = toggle.closest('.has-submenu');
            parent.classList.toggle('active');
        });
    });
document.querySelectorAll('.cargar-contenido').forEach(link => {
    link.addEventListener('click', function () {
        const url = this.getAttribute('data-url');
        fetch(url)
            .then(response => response.text())
            .then(html => {
                const contenido = document.getElementById('contenido-dinamico');
                if (contenido) {
                    contenido.innerHTML = html;

                    // ✅ Si es dashboard, cargar gráficos
                    if (url.includes("dashboard.php")) {
                        setTimeout(() => {
                            cargarGraficosDashboard();
                        }, 200); // Espera pequeña para que los canvas se inserten en el DOM
                    }
                }
            })
            .catch(err => console.error('Error al cargar el contenido:', err));
    });
});


});

document.querySelectorAll('.navegacion ul li > a').forEach(link => {
    link.addEventListener('click', function() {
        document.querySelectorAll('.navegacion ul li > a').forEach(el => el.classList.remove('active'));
        this.classList.add('active');
    });
});

document.querySelectorAll('.submenu li a').forEach(link => {
    link.addEventListener('click', function() {
        document.querySelectorAll('.submenu li a').forEach(el => el.classList.remove('selected'));
        this.classList.add('selected');
    });
});

function cambiarTexto() {
    console.log("¡Hiciste clic en el botón!");
}

function abrirModal(id) {
    document.getElementById(id).style.display = 'block';
}
function cerrarModal(id) {
    document.getElementById(id).style.display = 'none';
}
window.onclick = function(event) {
    const modales = document.querySelectorAll(".modal, .modaltablagrande"); 
    modales.forEach(modal => {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });
};

let monthlyChartInstance = null; // Para controlar el gráfico
let statusChartInstance = null;


//Login scripts

        function mostrarContrasena() {
            var input = document.getElementById("contraseña");
            input.type = document.getElementById("ver_contraseña").checked ? "text" : "password";
        }

//Buscar cliente
function buscarCliente() {
    console.log("Evento BUSCAR activado");
    const tipo = document.getElementById('tipo_busqueda').value;
    const valor = document.getElementById('valor_busqueda').value;
    console.log("Tipo de búsqueda seleccionado:", tipo); // Paso 2: Verificando el valor del combo
    console.log("Valor ingresado:", valor); // Paso 3: Verificando input del usuario
    if (tipo === "Tipo de búsqueda" || valor.trim() === "") {
        console.log("Falta seleccionar tipo o ingresar valor");
        alert("Selecciona un tipo de búsqueda y un valor válido.");
        return;
    }

    const url = '../login/buscarcliente.php?tipo=' + tipo + '&valor=' + valor;
    console.log("URL de fetch:", url); // Paso 4: URL generada

    fetch(url)
        .then(response => response.json())
        .then(data => {
            console.log("Respuesta:", data);
            if (data.existe) {
                document.getElementById('nombres').value = data.nombres;
                document.getElementById('apellido_paterno').value = data.apellido_paterno;
                document.getElementById('apellido_materno').value = data.apellido_materno;
                document.getElementById('dni').value = data.dni;
                document.getElementById('correo').value = data.correo;
                document.getElementById('telefono').value = data.telefono;
                document.getElementById('direccion').value = data.direccion;
                document.getElementById('id_empresa').value = data.id_empresa;

                setReadonlyState(true);
            } else {
                alert('Cliente no encontrado. Llenar manualmente.');
                setReadonlyState(false);
                limpiarCampos();
            }
        })
        .catch(error => console.error('Error:', error));
}

function setReadonlyState(state) {
    ['nombres', 'apellido_paterno', 'apellido_materno', 'dni', 'correo', 'telefono', 'direccion', 'id_empresa']
        .forEach(id => document.getElementById(id).readOnly = state);
}

function limpiarCampos() {
    ['nombres', 'apellido_paterno', 'apellido_materno', 'dni', 'correo', 'telefono', 'direccion', 'id_empresa']
        .forEach(id => document.getElementById(id).value = '');
}
// Este reemplaza al anterior DOMContentLoaded
document.addEventListener('click', function(event) {
    if (event.target && event.target.id === 'btnBuscar') { // Para búsqueda de clientes en otras páginas
        buscarCliente();
    }
    if (event.target && event.target.id === 'btnBuscarIncidencia') { // Para búsqueda de incidencias
        buscarIncidencias();
    }
    if (event.target && event.target.id === 'btnBuscarReport') { // Para búsqueda de reportes
        buscarReportes();
    }
    if (event.target && event.target.id === 'btnBuscarSeguimiento') { // Para búsqueda de seguimientos
        buscarSeguimientos(); // Por defecto, no limpia filtros
    }
    if (event.target && event.target.id === 'btnLimpiarFiltroSeguimiento') { // Para limpiar filtros de seguimientos
        buscarSeguimientos(true); // Indica que se deben limpiar los filtros
    }
    if (event.target && event.target.id === 'btnLimpiarFiltroReport') { // Para limpiar filtros de seguimientos
        buscarReportes(true); // Indica que se deben limpiar los filtros
    }
    if (event.target && event.target.id === 'btnLimpiarFiltroIncidencias') { // Para limpiar filtros de seguimientos
        buscarIncidencias(true); // Indica que se deben limpiar los filtros
    }
    if (event.target && event.target.id === 'btnGenerarReportePDF') {
        event.preventDefault(); // Prevenir la acción por defecto del enlace
        btnGenerarReportePDF();
    }
});

function btnGenerarReportePDF() {
    const tipoBusquedaElement = document.getElementById('tipo_busqueda_report');
    const valorBusquedaElement = document.getElementById('valor_busqueda');

    let tipoFiltro = '';
    let valorFiltro = '';

    if (tipoBusquedaElement && valorBusquedaElement) {
        tipoFiltro = tipoBusquedaElement.value;
        valorFiltro = valorBusquedaElement.value.trim();
    }

    const params = new URLSearchParams();
    if (tipoFiltro) { // Solo añadir parámetros si hay un tipo de búsqueda seleccionado
        params.append('tipo_filtro', tipoFiltro);
        if (valorFiltro) { // Y si hay un valor de búsqueda
             params.append('valor_filtro', valorFiltro);
        }
    }
    const url = `../php/generar_reporte_incidencias_pdf.php?${params.toString()}`;
    window.location.href = url; // Redirigir para descargar/ver el PDF
}

function buscarSeguimientos(limpiarFiltros = false) {
    const tipoBusquedaElement = document.getElementById('tipo_busqueda_seguimiento');
    const valorBusquedaElement = document.getElementById('valor_busqueda_seguimiento');
    const tablaBody = document.querySelector('#tablaSeguimientos tbody'); // Asumiendo que la tabla tiene id="tablaSeguimientos"

    if (!tablaBody) {
        console.error('No se encontró el tbody de la tabla de seguimientos (#tablaSeguimientos tbody).');
        return;
    }

    let params = new URLSearchParams();
    let tipoBusqueda = '';
    let valorBusqueda = '';

    if (limpiarFiltros) {
        if (tipoBusquedaElement) tipoBusquedaElement.selectedIndex = 0;
        if (valorBusquedaElement) valorBusquedaElement.value = '';
    } else {
        if (!tipoBusquedaElement || !valorBusquedaElement) {
            console.error("Elementos de búsqueda de seguimiento no encontrados en el DOM.");
            return;
        }
        tipoBusqueda = tipoBusquedaElement.value;
        valorBusqueda = valorBusquedaElement.value.trim();

        if (tipoBusqueda && valorBusqueda === '') {
            alert('Por favor, ingrese un valor para la búsqueda.');
            return;
        }
        if (!tipoBusqueda && valorBusqueda !== '') {
            alert('Por favor, seleccione un tipo de búsqueda.');
            return;
        }
        if (tipoBusqueda) { // Solo añadir parámetros si hay un tipo de búsqueda
            params.append('tipo_filtro', tipoBusqueda);
            params.append('valor_filtro', valorBusqueda);
        }
    }
    // Si limpiarFiltros es true, params se queda vacío, y el backend debería devolver todos los seguimientos.

    const urlBase = '../php/ajax_buscar_seguimientos.php';
    const queryString = params.toString();
    const fetchUrl = queryString ? `${urlBase}?${queryString}` : urlBase;

    // Actualizar URL del navegador
    const newBrowserUrl = queryString ? `${window.location.pathname}?${queryString}` : window.location.pathname;
    window.history.pushState({ path: newBrowserUrl }, '', newBrowserUrl);

    fetch(fetchUrl)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
            }
            return response.text();
        })
        .then(html => {
            tablaBody.innerHTML = html;
        })
        .catch(error => {
            console.error('Error en la petición fetch para seguimientos:', error);
            tablaBody.innerHTML = '<tr><td colspan="11" style="text-align:center; color:red;">Error al cargar los datos de seguimiento.</td></tr>';
        });
}
// Buscar incidencias
function buscarIncidencias() {
    const tipoBusquedaElement = document.getElementById('tipo_busqueda_incidencia');
    const valorBusquedaElement = document.getElementById('valor_busqueda');

    if (!tipoBusquedaElement || !valorBusquedaElement) {
        console.error("Elementos de búsqueda no encontrados en el DOM.");
        return;
    }

    const tipoBusqueda = tipoBusquedaElement.value;
    const valorBusqueda = valorBusquedaElement.value.trim();

    if (!tipoBusqueda) {
        alert('Por favor, seleccione un tipo de búsqueda.');
        return;
    }

    if ((tipoBusqueda === 'dni_cliente' || tipoBusqueda === 'prioridad') && valorBusqueda === '') {
        if (tipoBusqueda === 'dni_cliente') {
            alert('Por favor, ingrese un DNI para buscar.');
        } else { // prioridad
            alert('Por favor, ingrese un valor para la prioridad (ej: Alta, Media, Baja).');
        }
        return;
    }

    const params = new URLSearchParams();
    params.append('tipo_filtro', tipoBusqueda);
    params.append('valor_filtro', valorBusqueda);

    // Construir la URL para la petición fetch
    const url = `../php/ajax_buscar_incidencias.php?${params.toString()}`;

    // Guardar los parámetros de búsqueda actuales en la URL del navegador para persistencia (opcional)
    // Esto actualiza la URL sin recargar la página. Si el usuario recarga manualmente, se aplicarán los filtros.
    const newUrl = `${window.location.pathname}?${params.toString()}`;
    window.history.pushState({path: newUrl}, '', newUrl);

    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
            }
            return response.text(); // Esperamos HTML como texto
        })
        .then(html => {
            const tablaBody = document.querySelector('table tbody'); // Asumiendo una sola tabla en la página
            if (tablaBody) {
                tablaBody.innerHTML = html;
            } else {
                console.error('No se encontró el tbody de la tabla para actualizar.');
            }
        })
        .catch(error => {
            console.error('Error en la petición fetch:', error);
            const tablaBody = document.querySelector('table tbody');
            if (tablaBody) {
                tablaBody.innerHTML = '<tr><td colspan="9" style="text-align:center; color:red;">Error al cargar los datos. Por favor, intente de nuevo.</td></tr>';
            }
        });
}

// limpiar casillas
function buscarIncidencias(limpiarFiltros = false) {
    const tipoBusquedaElement = document.getElementById('tipo_busqueda_incidencia');
    const valorBusquedaElement = document.getElementById('valor_busqueda');
    const tablaBody = document.querySelector('#tablaIncidencias tbody'); // Asumiendo que la tabla tiene id="tablaSeguimientos"

    if (!tablaBody) {
        console.error('No se encontró el tbody de la tabla de seguimientos (#tablaReport tbody).');
        return;
    }

    let params = new URLSearchParams();
    let tipoBusqueda = '';
    let valorBusqueda = '';

    if (limpiarFiltros) {
        if (tipoBusquedaElement) tipoBusquedaElement.selectedIndex = 0;
        if (valorBusquedaElement) valorBusquedaElement.value = '';
    } else {
        if (!tipoBusquedaElement || !valorBusquedaElement) {
            console.error("Elementos de búsqueda de seguimiento no encontrados en el DOM.");
            return;
        }
        tipoBusqueda = tipoBusquedaElement.value;
        valorBusqueda = valorBusquedaElement.value.trim();

        if (tipoBusqueda && valorBusqueda === '') {
            alert('Por favor, ingrese un valor para la búsqueda.');
            return;
        }
        if (!tipoBusqueda && valorBusqueda !== '') {
            alert('Por favor, seleccione un tipo de búsqueda.');
            return;
        }
        if (tipoBusqueda) { // Solo añadir parámetros si hay un tipo de búsqueda
            params.append('tipo_filtro', tipoBusqueda);
            params.append('valor_filtro', valorBusqueda);
        }
    }
    // Si limpiarFiltros es true, params se queda vacío, y el backend debería devolver todos los seguimientos.

    const urlBase = '../php/ajax_buscar_incidencias.php';
    const queryString = params.toString();
    const fetchUrl = queryString ? `${urlBase}?${queryString}` : urlBase;

    // Actualizar URL del navegador
    const newBrowserUrl = queryString ? `${window.location.pathname}?${queryString}` : window.location.pathname;
    window.history.pushState({ path: newBrowserUrl }, '', newBrowserUrl);

    fetch(fetchUrl)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
            }
            return response.text();
        })
        .then(html => {
            tablaBody.innerHTML = html;
        })
        .catch(error => {
            console.error('Error en la petición fetch para seguimientos:', error);
            tablaBody.innerHTML = '<tr><td colspan="11" style="text-align:center; color:red;">Error al cargar los datos de seguimiento.</td></tr>';
        });

}

// limpiar casillas
function buscarReportes(limpiarFiltros = false) {
    const tipoBusquedaElement = document.getElementById('tipo_busqueda_report');
    const valorBusquedaElement = document.getElementById('valor_busqueda');
    const tablaBody = document.querySelector('#tablaReport tbody'); // Asumiendo que la tabla tiene id="tablaSeguimientos"

    if (!tablaBody) {
        console.error('No se encontró el tbody de la tabla de seguimientos (#tablaReport tbody).');
        return;
    }

    let params = new URLSearchParams();
    let tipoBusqueda = '';
    let valorBusqueda = '';

    if (limpiarFiltros) {
        if (tipoBusquedaElement) tipoBusquedaElement.selectedIndex = 0;
        if (valorBusquedaElement) valorBusquedaElement.value = '';
    } else {
        if (!tipoBusquedaElement || !valorBusquedaElement) {
            console.error("Elementos de búsqueda de seguimiento no encontrados en el DOM.");
            return;
        }
        tipoBusqueda = tipoBusquedaElement.value;
        valorBusqueda = valorBusquedaElement.value.trim();

        if (tipoBusqueda && valorBusqueda === '') {
            alert('Por favor, ingrese un valor para la búsqueda.');
            return;
        }
        if (!tipoBusqueda && valorBusqueda !== '') {
            alert('Por favor, seleccione un tipo de búsqueda.');
            return;
        }
        if (tipoBusqueda) { // Solo añadir parámetros si hay un tipo de búsqueda
            params.append('tipo_filtro', tipoBusqueda);
            params.append('valor_filtro', valorBusqueda);
        }
    }
    // Si limpiarFiltros es true, params se queda vacío, y el backend debería devolver todos los seguimientos.

    const urlBase = '../php/ajax_buscar_report.php';
    const queryString = params.toString();
    const fetchUrl = queryString ? `${urlBase}?${queryString}` : urlBase;

    // Actualizar URL del navegador
    const newBrowserUrl = queryString ? `${window.location.pathname}?${queryString}` : window.location.pathname;
    window.history.pushState({ path: newBrowserUrl }, '', newBrowserUrl);

    fetch(fetchUrl)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
            }
            return response.text();
        })
        .then(html => {
            tablaBody.innerHTML = html;
        })
        .catch(error => {
            console.error('Error en la petición fetch para seguimientos:', error);
            tablaBody.innerHTML = '<tr><td colspan="11" style="text-align:center; color:red;">Error al cargar los datos de seguimiento.</td></tr>';
        });

}


// codigo del dashboard

document.addEventListener("DOMContentLoaded", () => {
  fetch("../registros/data_dashboard.php")
    .then(res => res.json())
    .then(data => {
      if (data.error) {
        console.error("Error desde el backend:", data.error);
        return;
      }

      // Mostrar números estadísticos
      document.getElementById("totalIncidencias").textContent = data.total_incidencias;
      document.getElementById("totalClientes").textContent = data.total_clientes;
      document.getElementById("incidenciasPendientes").textContent = data.incidencias_pendientes;
      document.getElementById("incidenciasFinalizadas").textContent = data.incidencias_finalizadas;

      // Prioridades
      document.getElementById("prioridadAlta").textContent = data.prioridad.alta;
      document.getElementById("prioridadMedia").textContent = data.prioridad.media;
      document.getElementById("prioridadBaja").textContent = data.prioridad.baja;

      // Gráfico mensual
      const monthlyCtx = document.getElementById("monthlyChart").getContext("2d");
      new Chart(monthlyCtx, {
        type: "bar",
        data: {
          labels: data.meses,
          datasets: [{
            data: data.datos_mes,
            backgroundColor: 'rgba(255, 255, 255, 0.8)',
            borderColor: 'rgba(255, 255, 255, 1)',
            borderWidth: 2,
            borderRadius: 8,
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { display: false }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: { color: 'rgba(255, 255, 255, 0.8)' },
              grid: { color: 'rgba(255, 255, 255, 0.2)' }
            },
            x: {
              ticks: { color: 'rgba(255, 255, 255, 0.8)' },
              grid: { display: false }
            }
          }
        }
      });

      // Gráfico de estados
      const statusCtx = document.getElementById("statusChart").getContext("2d");
      new Chart(statusCtx, {
        type: "line",
        data: {
          labels: data.estados,
          datasets: [{
            data: data.datos_estados,
            borderColor: 'rgba(255, 255, 255, 1)',
            backgroundColor: 'rgba(255, 255, 255, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: 'rgba(255, 255, 255, 1)',
            pointBorderColor: 'rgba(255, 255, 255, 1)',
            pointRadius: 6,
            pointHoverRadius: 8,
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { display: false }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: { color: 'rgba(255, 255, 255, 0.8)' },
              grid: { color: 'rgba(255, 255, 255, 0.2)' }
            },
            x: {
              ticks: { color: 'rgba(255, 255, 255, 0.8)' },
              grid: { color: 'rgba(255, 255, 255, 0.2)' }
            }
          }
        }
      });

      console.log("Gráficos cargados exitosamente");
    })
    .catch(err => {
      console.error("Error al obtener los datos del dashboard:", err);
    });
});

function cargarGraficosDashboard() {
    fetch("../registros/data_dashboard.php")
        .then(res => res.json())
        .then(data => {
            if (data.error) return console.error("Error desde el backend:", data.error);

            document.getElementById("totalIncidencias").textContent = data.total_incidencias;
            document.getElementById("totalClientes").textContent = data.total_clientes;
            document.getElementById("incidenciasPendientes").textContent = data.incidencias_pendientes;
            document.getElementById("incidenciasFinalizadas").textContent = data.incidencias_finalizadas;
            document.getElementById("prioridadAlta").textContent = data.prioridad.alta;
            document.getElementById("prioridadMedia").textContent = data.prioridad.media;
            document.getElementById("prioridadBaja").textContent = data.prioridad.baja;

            const monthlyCtx = document.getElementById("monthlyChart")?.getContext("2d");
            if (monthlyCtx) {
                    if (monthlyChartInstance) {
                            monthlyChartInstance.destroy();
                        }
                new Chart(monthlyCtx, {
                    type: "bar",
                    data: {
                        labels: data.meses,
                        datasets: [{
                            data: data.datos_mes,
                            backgroundColor: 'rgba(255, 255, 255, 0.8)',
                            borderColor: 'rgba(255, 255, 255, 1)',
                            borderWidth: 2,
                            borderRadius: 8,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { color: 'rgba(255, 255, 255, 0.8)' },
                                grid: { color: 'rgba(255, 255, 255, 0.2)' }
                            },
                            x: {
                                ticks: { color: 'rgba(255, 255, 255, 0.8)' },
                                grid: { display: false }
                            }
                        }
                    }
                });
            }

            const statusCtx = document.getElementById("statusChart")?.getContext("2d");
            if (statusCtx) {
                 if (statusChartInstance) {
                        statusChartInstance.destroy();
                    }
                new Chart(statusCtx, {
                    type: "line",
                    data: {
                        labels: data.estados,
                        datasets: [{
                            data: data.datos_estados,
                            borderColor: 'rgba(255, 255, 255, 1)',
                            backgroundColor: 'rgba(255, 255, 255, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: 'rgba(255, 255, 255, 1)',
                            pointBorderColor: 'rgba(255, 255, 255, 1)',
                            pointRadius: 6,
                            pointHoverRadius: 8,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { color: 'rgba(255, 255, 255, 0.8)' },
                                grid: { color: 'rgba(255, 255, 255, 0.2)' }
                            },
                            x: {
                                ticks: { color: 'rgba(255, 255, 255, 0.8)' },
                                grid: { color: 'rgba(255, 255, 255, 0.2)' }
                            }
                        }
                    }
                });
            }
        })
        .catch(err => {
            console.error("Error al cargar datos del dashboard:", err);
        });
}

