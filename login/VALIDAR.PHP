<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = trim($_POST['usuario']);
    $contraseña = $_POST['contraseña'];

    require_once("CONEXIONBD.PHP");
    $conexion = obtenerConexion();

    $stmt = $conexion->prepare("SELECT contraseña FROM usuario WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($hashGuardado);
        $stmt->fetch();

        //echo "Usuario encontrado: $usuario<br>";
        //echo "Hash en BD: $hashGuardado<br>";
        //echo "Contraseña ingresada: $contraseña<br>";
		//echo "Hash generado de '123456': " . password_hash("123456", PASSWORD_DEFAULT) . "<br>";

        if (password_verify($contraseña, $hashGuardado)) {
            echo "Contraseña verificada correctamente.";
            $_SESSION['usuario'] = $usuario;
            header("Location: INDEX.PHP");
            exit();
        } else {
            echo "Contraseña incorrecta.<br>";
        }
    } else {
        echo "El usuario '$usuario' no existe en la base de datos.<br>";
    }

    // $_SESSION['error'] = "Usuario o contraseña incorrectos";
    // header("Location: LOGIN.PHP");
    exit();
}
?>
