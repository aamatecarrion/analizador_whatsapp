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
		if(!empty($_REQUEST) && isset($_COOKIE["id_usuario"])) {
			$id_chat = $_REQUEST["id_chat"];
			$nombre = $_REQUEST["nombre"];
		echo '<div class="mensajeeliminar">¿Seguro que quieres eliminar el chat con '.$nombre.'?<div>';
		echo '<div class="opcioneseliminar">';
		echo '<a href="eliminar.php?id_chat='.$id_chat.'" class="eliminareliminar">Eliminar</a> ';
		echo '<a href="chats.php"> Cancelar</a>';
		echo '</div>';											
		}
		else {
			header('Location: index.php');
		}
	?>

</body>
</html>