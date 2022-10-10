<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="icon" type="image/x-icon" href="proyectofavicon.png">
	<title>Analizador de WhatsApp</title>
</head>
<body>
	<a class="botonatras" href="chats.php">Atrás</a>
	<?php

		if(!empty($_REQUEST) && isset($_COOKIE["id_usuario"])) {
			$id_chat = $_REQUEST["id_chat"];
			$id_usuario = $_COOKIE['id_usuario'];

			$colores=array( "#9900ff" , "#0066FF" , "#FF00FF" , "#00cc00" , "#009126" , "#00a385" , "#808000" , "#FF0000" , "#008080" ,"#ff33cc","#8b5700","#ffbc00","#00d9d9","#187498","#36AE7C","#FF0000" );


			$mysqli = new mysqli("127.0.0.1", "super", "123456", "whatsapp");
			$cadena="SELECT nombre, numero_mensajes, numero_participantes, mensajes_dia, primer_mensaje, ultimo_mensaje FROM chats WHERE id_chat='".$id_chat."' AND id_usuario='".$id_usuario."'";
			$consulta_chat = $mysqli->query($cadena);
			if ( $fila_chat = $consulta_chat -> fetch_assoc() ){
				$nombre=$fila_chat["nombre"];
				$numero_mensajes=$fila_chat["numero_mensajes"];
				$numero_participantes=$fila_chat["numero_participantes"];
				$mensajes_dia=$fila_chat["mensajes_dia"];
			}
			echo '<h1>'.$nombre.'</h1>';
			echo '<div class="tpm"><span><div>Total de mensajes</div><div>'.$numero_mensajes.'</div></span>';
			echo '<span><div>Participantes</div><div>'.$numero_participantes.'</div></span>';
			echo '<span><div>Mensajes/día</div><div>'.$mensajes_dia.'</div></span></div>';
			$cadena="SELECT primer_mensaje, ultimo_mensaje FROM chats WHERE id_chat=".$id_chat;
			$consulta_chat = $mysqli->query($cadena);
			$fila_chat = $consulta_chat -> fetch_assoc();
			$primer_mensaje = $fila_chat["primer_mensaje"];
			$ultimo_mensaje = $fila_chat["ultimo_mensaje"];
			$primer_mensaje_dias=floor((time()-strtotime($primer_mensaje))/60/60/24);
			$ultimo_mensaje_dias=floor((time()-strtotime($ultimo_mensaje))/60/60/24);

			$primermensaje=date_create_from_format("Y-m-d H:i:s", $primer_mensaje);
            $primermensaje=date_format($primermensaje,"j/n/y G:i");
            $ultimomensaje=date_create_from_format("Y-m-d H:i:s", $ultimo_mensaje);
            $ultimomensaje=date_format($ultimomensaje,"j/n/y G:i");


			echo '<div class="primerultimo"><div>Primer mensaje: '.$primermensaje.' (hace '.$primer_mensaje_dias.' días)</div>';
			echo '<div>Último mensaje: '.$ultimomensaje.' (hace '.$ultimo_mensaje_dias.' días)</div></div>';
			echo '<div class="graficos">';
			echo '<div class="graficohora">';
			echo '<h3>Mensajes por hora del día</h3>';
			
			$cadena="SELECT GREATEST(m0, m1, m2, m3, m4, m5, m6, m7, m8, m9, m10, m11, m12, m13, m14, m15, m16, m17, m18, m19, m20, m21, m22, m23) AS maximo FROM chats WHERE id_chat=".$id_chat;
			$consulta_maximo_hora = $mysqli->query($cadena);
			if  ( $fila_maximo_hora=$consulta_maximo_hora -> fetch_array() )
			$maximo_hora=$fila_maximo_hora["maximo"];

			$cadena="SELECT m0, m1, m2, m3, m4, m5, m6, m7, m8, m9, m10, m11, m12, m13, m14, m15, m16, m17, m18, m19, m20, m21, m22, m23 FROM chats WHERE id_chat=".$id_chat;
			$consulta_mensajes_hora = $mysqli->query($cadena);
			if  ( $fila_mensajes_hora=$consulta_mensajes_hora -> fetch_array() ) {
				for ( $i=0; $i < 24 ; $i++ ) { 
					if ( $i < 10 ){
						echo '<span>0'.$i.' </span>';
					}
					else {
						echo '<span>'.$i.' </span>';
					}
					$porcentaje_mensajes_hora = ceil(( $fila_mensajes_hora[$i] / $maximo_hora) * 75 );
					if ($porcentaje_mensajes_hora > 75 ) {
					$porcentaje_mensajes_hora=75;
					}
					$color=$colores[array_rand($colores)];
					echo '<div class="barramensajeshora" style="width: '.$porcentaje_mensajes_hora.'%; background-color: '.$color.';"></div>';
					//for ($j=0; $j < $porcentaje_mensajes_hora ; $j++) { 
					//	echo '<span>|</span>';
					//}
					echo '<span> '.$fila_mensajes_hora[$i].'</span><br>'; 	
				}
			}
			echo '</div><div class="graficosemana">';
			echo '<h3>Mensajes por día de la semana</h3>';
			$cadena="SELECT GREATEST(m_lunes, m_martes, m_miercoles, m_jueves, m_viernes, m_sabado, m_domingo) AS maximo FROM chats WHERE id_chat=".$id_chat;
			$consulta_maximo_semana = $mysqli->query($cadena);
			$fila_maximo_semana=$consulta_maximo_semana -> fetch_array();
			$maximo_semana=$fila_maximo_semana["maximo"];

			$cadena="SELECT m_lunes, m_martes, m_miercoles, m_jueves, m_viernes, m_sabado, m_domingo FROM chats WHERE id_chat=".$id_chat;
			$consulta_mensajes_semana = $mysqli->query($cadena);
			$fila_mensajes_semana=$consulta_mensajes_semana -> fetch_array();
			
			$dias_de_la_semana = array("Lunes","Martes","Miercoles","Jueves","Viernes","Sábado","Domingo");

			for ($i=0; $i < 7 ; $i++) { 

				echo '<div class="diasemana"><span>'.$dias_de_la_semana[$i].' </span><br>';
				
				$porcentaje_mensajes_semana = ceil(( $fila_mensajes_semana[$i] / $maximo_semana) * 83 );
				if ($porcentaje_mensajes_semana > 83 ) {
					$porcentaje_mensajes_semana=83;
				}
				$color=$colores[array_rand($colores)];
				echo '<div class="barramensajessemana" style="width: '.$porcentaje_mensajes_semana.'%; background-color: '.$color.';"></div>';
				echo '<span> '.$fila_mensajes_semana[$i].'</span></div>'; 
				
			}
			echo '</div><div class="graficomes">';
			echo '<h3>Mensajes por mes</h3>';
			$cadena="SELECT numero_mensajes_mes FROM meses WHERE id_chat='".$id_chat."' ORDER BY numero_mensajes_mes DESC LIMIT 1";
			$consulta_mes_maximo = $mysqli->query($cadena);
			if ($fila_mes_maximo = $consulta_mes_maximo -> fetch_assoc() ) {
				$numero_mes_maximo=$fila_mes_maximo["numero_mensajes_mes"];
			}
			$cadena="SELECT mes, numero_mensajes_mes FROM meses WHERE id_chat=".$id_chat;
			$consulta_mensajes_mes = $mysqli->query($cadena);
			
			while ( $fila_mensajes_mes = $consulta_mensajes_mes -> fetch_array() ) {
				$numero_mensajes_mes = $fila_mensajes_mes["numero_mensajes_mes"];
				$mes = $fila_mensajes_mes["mes"];
				
				$porcentaje_mensajes_mes = ceil(( $numero_mensajes_mes / $numero_mes_maximo) * 58 );
				if ($porcentaje_mensajes_mes > 58 ) {
					$porcentaje_mensajes_mes=58;
				}
				echo '<div class="mes">';
				echo '<span >'.$mes.' </span>';
				$color=$colores[array_rand($colores)];
				echo '<span class="barramensajesmes" style="width: '.$porcentaje_mensajes_mes.'%; background-color: '.$color.';"></span>';
				echo '<span> '.$numero_mensajes_mes.'</span></div>';
			}
			echo '</div><div class="graficoparticipacion"><h3>Participación</h3>';
			$cadena="SELECT nombre, numero_mensajes, participacion FROM participantes WHERE id_chat='".$id_chat."' ORDER BY numero_mensajes DESC";
			$consulta_participante = $mysqli->query($cadena);
			while ( $fila_participante = $consulta_participante -> fetch_assoc() ) {
				$color=$colores[array_rand($colores)];
				echo '<div class="parpart"><div style="color: '.$color.';">'.$fila_participante["nombre"].': '.$fila_participante["numero_mensajes"].'</div>';
				$participacion=($fila_participante["participacion"]);
				$barraparticipacion=$participacion*0.85;
				if ($barraparticipacion > 85 ) {
					$barraparticipacion=85;
				}
				echo '<div class="barraparticipacion" style="width: '.$barraparticipacion.'%; background-color: '.$color.';"></div>';
				echo '<span style="color: '.$color.';"> '.$fila_participante["participacion"].'%</span></div>';			
			}
			echo "</div>";
			echo '</div>';
			
		}
		else {
			header('Location: index.php');
		}
	?>
</body>
</html>