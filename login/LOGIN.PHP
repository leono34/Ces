<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>SISTEMA DE INCIDENCIAS DE CLIENTES</title>
	<link rel="stylesheet" type="text/css" href="../css/ESTILOS.CSS">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
	<script src="../js/main.js"></script>
</head>
<body>
	<form action="VALIDAR.PHP" method="post" action="">
		<div class="wrapper">
			<h1>SISTEMA DE INCIDENCIAS</h1>
			<h3>Ingresa tus datos para acceder</h3><br>
			<div class="input-box">
				<input type="text" name="usuario" placeholder="usuario" required>	
				<i class="bi bi-person-fill"></i>	
			</div>
			<div class="input-box">
				<input type="password" name="contraseña" id="contraseña" placeholder="contraseña" autocomplete="off" required>	
				<i class="bi bi-lock-fill" style="color: white;"></i>
			</div>
			<div class="remember-forgot">
				<label><input type="checkbox" id="ver_contraseña" onclick="mostrarContrasena()"> Mostrar Contraseña</label>				
			</div>
			<button type="submit" class="btn btn-outline-success">INGRESAR</button>
		</div>
	</form>
	<?php session_start(); if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger text-center">
        <?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
    </div>
	<?php endif; ?>
</body>
</html>
