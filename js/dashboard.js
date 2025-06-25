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
