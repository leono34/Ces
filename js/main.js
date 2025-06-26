
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
    if (event.target && event.target.id === 'btnBuscar') {
        buscarCliente();
    }
});


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
