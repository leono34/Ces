<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema de Incidencias</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <style>
            .dashboard-container {
                padding: 20px;
                background-color: #f8f9fa;
                min-height: 100vh;
            }

            .dashboard-header {
                margin-bottom: 30px;
            }

            .dashboard-title {
                font-size: 2.5rem;
                font-weight: bold;
                color: #374724;
                margin-bottom: 10px;
            }

            .dashboard-subtitle {
                color: #6c757d;
                font-size: 1.1rem;
            }

            .stats-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                gap: 20px;
                margin-bottom: 30px;
            }

            .stat-card {
                background: white;
                border-radius: 15px;
                padding: 25px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                position: relative;
                overflow: hidden;
            }

            .stat-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 4px;
            }

            .stat-card.pink::before {
                background: linear-gradient(135deg, #e91e63, #f06292);
            }

            .stat-card.green::before {
                background: linear-gradient(135deg, #4caf50, #81c784);
            }

            .stat-card.blue::before {
                background: linear-gradient(135deg, #2196f3, #64b5f6);
            }

            .stat-card.orange::before {
                background: linear-gradient(135deg, #ff9800, #ffb74d);
            }

            .stat-icon {
                width: 60px;
                height: 60px;
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 15px;
                font-size: 24px;
                color: white;
            }

            .stat-icon.pink {
                background: linear-gradient(135deg, #e91e63, #f06292);
            }

            .stat-icon.green {
                background: linear-gradient(135deg, #4caf50, #81c784);
            }

            .stat-icon.blue {
                background: linear-gradient(135deg, #2196f3, #64b5f6);
            }

            .stat-icon.orange {
                background: linear-gradient(135deg, #ff9800, #ffb74d);
            }

            .stat-number {
                font-size: 2.5rem;
                font-weight: bold;
                color: #2c3e50;
                margin-bottom: 5px;
            }

            .stat-label {
                color: #6c757d;
                font-size: 1rem;
                margin-bottom: 10px;
            }

            .stat-change {
                font-size: 0.9rem;
                display: flex;
                align-items: center;
                gap: 5px;
            }

            .stat-change.positive {
                color: #4caf50;
            }

            .stat-change.negative {
                color: #f44336;
            }

            .charts-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
                gap: 20px;
                margin-bottom: 30px;
            }

            .chart-card {
                background: white;
                border-radius: 15px;
                padding: 25px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }

            .chart-card.pink {
                background: linear-gradient(135deg, #e91e63, #f06292);
                color: white;
            }

            .chart-card.green {
                background: linear-gradient(135deg, #4caf50, #81c784);
                color: white;
            }

            .chart-card.dark {
                background: linear-gradient(135deg, #37474f, #546e7a);
                color: white;
            }

            .chart-title {
                font-size: 1.3rem;
                font-weight: bold;
                margin-bottom: 15px;
            }

            .chart-subtitle {
                font-size: 0.9rem;
                opacity: 0.8;
                margin-bottom: 20px;
            }

            .chart-container {
                position: relative;
                height: 200px;
            }

            .priority-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 15px;
                margin-top: 20px;
            }

            .priority-item {
                background: rgba(255, 255, 255, 0.1);
                border-radius: 10px;
                padding: 15px;
                text-align: center;
            }

            .priority-number {
                font-size: 2rem;
                font-weight: bold;
                margin-bottom: 5px;
            }

            .priority-label {
                font-size: 0.9rem;
                opacity: 0.9;
            }
            
        </style>
</head>
<body>
    <div class="dashboard-container">

        <div class="dashboard-header">
            <h1 class="dashboard-title">Dashboard</h1>
            <p class="dashboard-subtitle">Sistema de Gestión de Incidencias</p>
        </div>

        <!-- Tarjetas de estadísticas -->
        <div class="stats-grid">
            <div class="stat-card pink">
                <div class="stat-icon pink">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-number"id="totalIncidencias"></div>
                <div class="stat-label">Total Incidencias</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i>
                    <span>+12% desde el mes pasado</span>
                </div>
            </div>

            <div class="stat-card green">
                <div class="stat-icon green">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-number"id="totalClientes"></div>
                <div class="stat-label">Total Clientes</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i>
                    <span>+8% desde el mes pasado</span>
                </div>
            </div>

            <div class="stat-card blue">
                <div class="stat-icon blue">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-number"id="incidenciasPendientes"></div>
                <div class="stat-label">Incidencias Pendientes</div>
                <div class="stat-change negative">
                    <i class="fas fa-arrow-down"></i>
                    <span>-5% desde ayer</span>
                </div>
            </div>

            <div class="stat-card orange">
                <div class="stat-icon orange">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-number"id="incidenciasFinalizadas"></div>
                <div class="stat-label">Incidencias Resueltas</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i>
                    <span>+15% desde ayer</span>
                </div>
            </div>
        </div>

        <!-- Gráficos -->
        <div class="charts-grid">
            <!-- Gráfico de Incidencias por Mes -->
            <div class="chart-card pink">
                <div class="chart-title">Incidencias por Mes</div>
                <div class="chart-subtitle">Últimos 6 meses de actividad</div>
                <div class="chart-container">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>

            <!-- Gráfico de Estados -->
            <div class="chart-card green">
                <div class="chart-title">Estados de Incidencias</div>
                <div class="chart-subtitle">Distribución actual por estado</div>
                <div class="chart-container">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>

            <!-- Prioridades -->
            <div class="chart-card dark">
                <div class="chart-title">Incidencias por Prioridad</div>
                <div class="chart-subtitle">Clasificación actual</div>
                <div class="priority-grid">
                    <div class="priority-item">
                        <div class="priority-number" id="prioridadAlta"></div>
                        <div class="priority-label">Alta Prioridad</div>
                    </div>
                    <div class="priority-item">
                        <div class="priority-number" id="prioridadMedia"></div>
                        <div class="priority-label">Media Prioridad</div>
                    </div>
                    <div class="priority-item">
                        <div class="priority-number" id="prioridadBaja"></div>
                        <div class="priority-label">Baja Prioridad</div>
                    </div>
                </div>
            </div>
            <!-- Gráfico de Test
            <div class="chart-card  orange">
                <div class="chart-title">Gráfico de Estados CLientes</div>
                <div class="chart-subtitle">Gráfico de prueba para verificar funcionalidad</div>
                <div class="chart-container">
                    <canvas id="testChart"></canvas>
            </div> -->

        </div>
    </div>


</body>
</html>
