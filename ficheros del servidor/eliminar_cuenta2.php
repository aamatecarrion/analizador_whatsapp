<?php

	if(isset($_COOKIE["id_usuario"])) {
			$id_usuario = $_COOKIE["id_usuario"];
			$mysqli = new mysqli("127.0.0.1", "super", "123456", "whatsapp");
			$cadena="DELETE FROM `usuarios` WHERE `id_usuario` = '".$id_usuario."'";
			$consulta = $mysqli->query($cadena);
		if($consulta){
			header('Location: index.php');		
		}
		else {
			header('Location: perfil.php');
		}
	}
?>