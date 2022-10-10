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
	<nav><span>PERFIL</span>
	<a href="chats.php">CHATS</a></nav>
	<a class="cerrarsesion" href="login.php">Cerrar sesión</a>
	<?php 
	if(isset($_COOKIE["id_usuario"])) {

		$mysqli = new mysqli("127.0.0.1", "super", "123456", "whatsapp");
		if ($mysqli->connect_error) {
			echo "Connection failed: " . $mysqli->connect_error;
		}

		//guardar el id de usuario en la variable id_usuario a partir de la cookie

		$id_usuario=$_COOKIE["id_usuario"];
		
		//guardar el nombre del usuario en la variable nombre

		$cadena = "SELECT nombre FROM usuarios WHERE id_usuario='".$id_usuario."'";
		$consulta = $mysqli->query($cadena);	
		if ( $fila = $consulta -> fetch_assoc() ) {
			$nombre = $fila["nombre"];
		}

		//guardar el id del último chat subido o actualizado en la variable ultimo_chat


		$cadena = "SELECT ultimo_chat FROM usuarios WHERE id_usuario='".$id_usuario."'";
		$consulta = $mysqli->query($cadena);	
		if ( $fila = $consulta -> fetch_assoc() ) {
			$ultimo_chat = $fila["ultimo_chat"];
		}
		if (empty($ultimo_chat)) {
			
		}
		
		//guardar el nombre y la fecha de subida del ultimo chat en una variable

		$cadena = "SELECT nombre,fecha_subida FROM chats WHERE id_chat='".$ultimo_chat."'";
		$consulta = $mysqli->query($cadena);	
		if ( $fila = $consulta -> fetch_assoc() ) {
			$nombre_chat = $fila["nombre"];
			$fecha_subida = $fila["fecha_subida"];
		}
		$nombre_fecha_ultimo_chat = $nombre_chat." (".$fecha_subida.")";
		

		//obtener el número de chats de ese usuario

		$cadena = "SELECT count(*) FROM chats WHERE id_usuario='".$id_usuario."'";
		$consulta = $mysqli->query($cadena);	
		if ( $fila = $consulta -> fetch_assoc() ) {
			$total_chats = $fila["count(*)"];
		}
		
		//obtener el total de mensajes que tiene guardados el usuario

		$cadena = "SELECT total_mensajes FROM usuarios WHERE id_usuario='".$id_usuario."'";
		$consulta = $mysqli->query($cadena);	
		if ( $fila = $consulta -> fetch_assoc() ) {
			$total_mensajes = $fila["total_mensajes"];
		}
		
		//obtener la fecha de registro del usuario

		$cadena = "SELECT fecha_registro FROM usuarios WHERE id_usuario='".$id_usuario."'";
		$consulta = $mysqli->query($cadena);	
		if ( $fila = $consulta -> fetch_assoc() ) {
			$fecha_registro = $fila["fecha_registro"];
		}
		
		if (empty($ultimo_chat)) {
			$nombre_fecha_ultimo_chat = "no hay ningún chat";
			$total_mensajes = "0";
		}

		//mostrar la información

		echo "<h1>Hola " .$nombre. "</h1>";
		echo '<div class="divperfil">';
		echo '<div class="datosusuario">';
		echo "<p>Usuario: " .$id_usuario. "</p>";
		echo "<p>Nombre: " .$nombre. "</p>";
		echo "<p>Último chat subido: " .$nombre_fecha_ultimo_chat. "</p>";
		echo "<p>Total de chats: " .$total_chats. "</p>";
		echo "<p>Número total de mensajes: " .$total_mensajes. "</p>";
		echo "<p>Fecha de registro: " .$fecha_registro. "</p>";
		echo '</div>';
		echo '<p id="eliminarcuenta"><a href="eliminar_cuenta.php">Eliminar cuenta</a></p>';
		echo '</div>';
			
	}
	else {
		header('Location: index.php');
	}
	?>
</body>
</html>