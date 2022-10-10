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
	<h1>Registro</h1>
	<a class="botonatras" href="index.php">Atrás</a>
	<form class="formregistro" action="nuevo_usuario.php" onSubmit = "return checkpass(this)" method="post">
		<input type="text" name="nombre" placeholder="Nombre"><br>
		<input type="text" name="usuario" placeholder="Usuario"><br>
		<input type="password" name="pass1" placeholder="Contraseña"><br>
		<input type="password" name="pass2" placeholder="Repetir contraseña"><br>
		<input class="submitregistro" type="submit" value="Registrarse">
	</form>
	<div id="mensajeregistro"><?php echo $_REQUEST['mensaje'] ?><div>
	<script>
	    function checkpass(form) {
	        nombre = form.nombre.value;
	        usuario = form.usuario.value;
	        password1 = form.pass1.value;
	        password2 = form.pass2.value;

	        // If password not entered
	        if (nombre == '') {
	        	document.getElementById('mensajeregistro').innerHTML = "Por favor introduce tu nombre";
	        	return false;
	        }
	        else if (usuario == '') {
	        	document.getElementById('mensajeregistro').innerHTML = "Por favor introduce un nombre de usuario";
	        	return false;
	        }
	        else if (password1 == '') {
	        	document.getElementById('mensajeregistro').innerHTML = "Por favor introduce la contraseña";
	        	return false;
	        }
	        else if (password2 == ''){
				document.getElementById('mensajeregistro').innerHTML = "Por favor confirma la contraseña";			                      
				return false;
			}
	        else if (password1 != password2) {
	    		document.getElementById('mensajeregistro').innerHTML = "Las contraseñas no coindicen";
	            return false;
	        }
	        else{
	            return true;
	        }
	    }
    </script>
</body>
</html>