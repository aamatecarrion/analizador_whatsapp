<?php
	if($_POST) {
		if ((!empty($_POST["nombre"])) && (!empty($_POST["usuario"])) && (!empty($_POST["pass1"])) && (!empty($_POST["pass2"]))){
				$nombre=$_POST["nombre"];
				$id_usuario=$_POST["usuario"];
				$pass1=$_POST["pass1"];
				$pass2=$_POST["pass2"];
			if ( $pass1 == $pass2 ) {
				$mysqli = new mysqli("127.0.0.1", "super", "123456", "whatsapp");
				if ($mysqli->connect_error) {
					echo "Connection failed: " . $mysqli->connect_error;
				}
				$nombre=$mysqli->real_escape_string($nombre);
				$id_usuario=$mysqli->real_escape_string($id_usuario);
				$pass1=$mysqli->real_escape_string($pass1);
				$fecha_actual=date("Y-m-d H:i:s");
				$pass=password_hash($pass1, PASSWORD_DEFAULT);

				$cadena="INSERT INTO `usuarios` (`id_usuario`, `pass`, `fecha_registro`, `nombre`, `total_mensajes`, `ultimo_chat`) VALUES ( '".$id_usuario."' , '".$pass."' , '".$fecha_actual."' , '".$nombre."' , NULL , NULL );";
				echo $cadena."<br>";
				$consulta = $mysqli->query($cadena);	
				if ($consulta) {					
					setcookie("id_usuario", $id_usuario);
					header('Location: perfil.php');
				}
				else {
					header('Location: registrarse.php?mensaje=El nombre de usuario '.$id_usuario.' ya está en uso');
				}
			}
			else {
				header('Location: index.php');
				
			}
		}
		else {
			header('Location: index.php');
		}
	}
?>