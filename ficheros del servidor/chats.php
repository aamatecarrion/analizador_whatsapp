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
	<nav>
		<a href="perfil.php">PERFIL</a>
		<span>CHATS</span>
	</nav>
		<a class="cerrarsesion" href="login.php">Cerrar sesión</a>
		<div class="listachats">
		<a class="subirchat" href="subir.php">+ Subir chat&nbsp</a>
	<?php

		$id_usuario = $_COOKIE["id_usuario"];
		$mysqli = new mysqli("127.0.0.1", "super", "123456", "whatsapp");
		$cadena="SELECT id_chat, nombre, fecha_subida, numero_mensajes FROM chats WHERE id_usuario='$id_usuario' ORDER BY fecha_subida DESC";
		$consulta = $mysqli->query($cadena);
		echo '<table>
				<tr>
					<th>Nombre</th>
					<th>Fecha de subida</th>
					<th>Nº de mensajes</th>
					<th></th>
				</tr>';
		while ($fila = $consulta -> fetch_assoc()){
			$id_chat=$fila["id_chat"];
			$nombre=$fila["nombre"];
			$fecha_subida=$fila["fecha_subida"];
			$numero_mensajes=$fila["numero_mensajes"];
			$fechasubida=date_create_from_format("Y-m-d H:i:s", $fecha_subida);
                    $fechasubida=date_format($fechasubida,"j/n/y G:i:s");
			echo '<tr class="filalista" onclick="window.location.href=\'estadisticas.php?id_chat='.$id_chat.'\'">';
				echo '<td>'.$nombre.'</a></td>';
				echo '<td>'.$fechasubida.'</td>';
				echo '<td>'.$numero_mensajes.'</td>';
				echo '<td><a class="eliminarchat" href="eliminar_chat.php?id_chat='.$id_chat.'&nombre='.$nombre.'">Eliminar</a></td>';
			echo '</tr>';
		}
		echo '</table>';
	?>
	</div>
</body>
</html>