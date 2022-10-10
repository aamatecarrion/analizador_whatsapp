<?php

	if(!empty($_REQUEST) && isset($_COOKIE["id_usuario"])) {
			$id_chat = $_REQUEST["id_chat"];
			$id_usuario = $_COOKIE['id_usuario'];
			$mysqli = new mysqli("127.0.0.1", "super", "123456", "whatsapp");
			$cadena="DELETE FROM `chats` WHERE `id_chat` =".$id_chat;
			$consulta = $mysqli->query($cadena);
		if($consulta){
				//actualizar los datos
				//obtener el chat con el número de id más alto, osea el más reciente
				$cadena="SELECT id_chat FROM chats WHERE id_usuario='".$id_usuario."' ORDER BY id_chat DESC LIMIT 1";
		        $consulta_id_chat = $mysqli->query($cadena);
		        if ( $fila_id_chat = $consulta_id_chat -> fetch_assoc() ) {
		            //guardar el id_chat en la variable id_ultimo_chat
		            $id_ultimo_chat=$fila_id_chat["id_chat"];
		        }
		        //añadir el id_chat del ultimo chat subido a la tabla usuarios
		        $cadena='UPDATE `usuarios` SET `ultimo_chat` = "'.$id_ultimo_chat.'" WHERE `id_usuario` = "'.$id_usuario.'"';
		        $consulta_update = $mysqli->query($cadena);
				
		        //sumar el número total de mensajes

		        $cadena="SELECT COUNT(*) AS numero FROM mensajes JOIN chats ON chats.id_chat=mensajes.id_chat WHERE chats.id_usuario = '".$id_usuario."'";
		        $consulta = $mysqli->query($cadena);            
		        if ( $fila = $consulta -> fetch_assoc() ) {
		            $numero = $fila["numero"];
		            $cadena='UPDATE `usuarios` SET `total_mensajes` = "'.$numero.'" WHERE `id_usuario` = "'.$id_usuario.'"';
		            $consulta_update = $mysqli->query($cadena);
		        }
		        
				header('Location: chats.php');		
		}
		else {
			header('Location: index.php');
		}
	}
?>