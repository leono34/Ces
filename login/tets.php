
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Test Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #222;
            color: #fff;
            padding: 20px;
        }
        canvas {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
        }
    </style>
</head>
<body>
    <h2>Gráfico de prueba</h2>
    <canvas id="testChart" width="400" height="200"></canvas>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            console.log("Versión Chart.js:", Chart?.version || "No cargado");

            const ctx = document.getElementById('testChart')?.getContext('2d');
            if (!ctx) {
                console.error("Canvas no encontrado");
                return;
            }

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May'],
                    datasets: [{
                        label: 'Incidencias',
                        data: [3, 2, 4, 1, 5],
                        backgroundColor: 'rgba(255, 99, 132, 0.7)',
                        borderRadius: 10
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            labels: {
                                color: '#000'
                            }
                        }
                    },
                    scales: {
                        x: {
                            ticks: { color: '#000' }
                        },
                        y: {
                            ticks: { color: '#000' }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>
