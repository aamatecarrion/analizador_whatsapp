<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Analizador de WhatsApp</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="icon" type="image/x-icon" href="proyectofavicon.png">
</head>
<body>
	<h1>Iniciar sesión</h1>
	<form action="login.php" method="post" class="formulariologin">
		<input class="logininput" type="text" name="usuario" placeholder="Usuario"><br>
		<input class="logininput" type="password" name="pass" placeholder="Contraseña"><br>
		<input class="submitlogin" type="submit" value="INICIAR SESIÓN" ><br>
		<div id="divnotienescuenta"><span id="registrarse">¿No tienes una cuenta? <a href="registrarse.php">Regístrate</a></span></div>
	</form>
	<div id="mensajeindex"><?php echo $_REQUEST['mensaje'] ?></div>
</body>
</html>