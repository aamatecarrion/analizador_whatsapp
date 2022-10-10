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
		$colores=array( "#9900ff" , "#0066FF" , "#FF00FF" , "#00cc00" , "#009126" , "#00a385" , "#808000" , "#FF0000" , "#008080" ,"#ff33cc","#8b5700","#ffbc00","#00d9d9","#187498","#36AE7C","#FF0000" );
	?>
	<a class="botonatras" href="chats.php">Atrás</a>
	<nav>
		<a href="perfil.php">PERFIL</a>
		<span>CHATS</span>
	</nav>
	<div class="formulariosubir">
	<p>Para subir un chat debes haberlo exportado en formato txt. Si subes un chat con un nombre ya existente se añadirán los mensajes nuevos al chat.</p>
	<form action="analizar.php" method="post" enctype="multipart/form-data">
  		<input type="file" name="archivo"><br><br>
		<input type="submit" value="Subir" onclick="cargando()">
	</form>
	</div>
	<script type="text/javascript">
		function cargando() {
			document.getElementById('cargando').style = "display: block;"
		}
	</script>
	<div class="cargando" id="cargando" style="display: none;"><div class="lds-roller"><div style="background: <?php echo $colores[array_rand($colores)];?>;"></div><div  style="background: <?php echo $colores[array_rand($colores)];?>;"></div><div  style="background: <?php echo $colores[array_rand($colores)];?>;"></div><div  style="background: <?php echo $colores[array_rand($colores)];?>;"></div><div  style="background: <?php echo $colores[array_rand($colores)];?>;"></div><div  style="background: <?php echo $colores[array_rand($colores)];?>;"></div><div  style="background: <?php echo $colores[array_rand($colores)];?>;"></div><div  style="background: <?php echo $colores[array_rand($colores)];?>;"></div></div><div class="mensajecargando">Analizando chat</div></div>
	<?php
	}
	else {
		header('Location: index.php');
	}
	?>
	
</body>
</html>