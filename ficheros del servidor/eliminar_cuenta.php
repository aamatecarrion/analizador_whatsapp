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
	<?php
		if(isset($_COOKIE["id_usuario"])) {
			$id_usuario = $_COOKIE["id_usuario"];
			echo '<div class="mensajeeliminar">¿Seguro que quieres eliminar tu cuenta?</div>';			
			echo '<div class="opcioneseliminar"><a href="eliminar_cuenta2.php" class="eliminareliminar">Eliminar</a> ';
			echo '<a href="perfil.php"> Cancelar</a></div>';											
		}
		else {
			header('Location: index.php');
		}
	?>

</body>
</html>