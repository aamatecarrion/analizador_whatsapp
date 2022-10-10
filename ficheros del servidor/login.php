<?php
	if($_POST) {
		if ((!empty($_POST["usuario"])) && (!empty($_POST["pass"]))){
			$id_usuario=$_POST["usuario"];
			$pass=$_POST["pass"];
			$mysqli = new mysqli("127.0.0.1", "super", "123456", "whatsapp");
			if ($mysqli->connect_error) {
				echo "Connection failed: " . $mysqli->connect_error;
				header('Location: index.php?mensaje=No se ha podido conectar a la base de datos: '.$mysqli->connect_error.'');
			}
			$id_usuario=$mysqli->real_escape_string($id_usuario);
			$pass=$mysqli->real_escape_string($pass);

			$cadena="SELECT pass FROM usuarios WHERE id_usuario='".$id_usuario."'";
			$consulta = $mysqli->query($cadena);	
			if ($fila = $consulta -> fetch_assoc()){
				$hash = $fila["pass"];
				if ( password_verify($pass, $hash)) {
					setcookie("id_usuario",$id_usuario);
					header('Location: perfil.php');
				}
				else {
					header('Location: index.php?mensaje=La contraseña no es correcta');
				}
			}
			else {
				header('Location: index.php?mensaje=El usuario '.$id_usuario.' no existe');
			}
		}
		else {
			header('Location: index.php?mensaje=Introduce tu usuario y contraseña');
		}
	}
	else {
		setcookie("id_usuario","");
		setcookie("pass","");
		header('Location: index.php');
	}
?>