<?php
    if(!file_exists($_FILES['archivo']['tmp_name']) || !is_uploaded_file($_FILES['archivo']['tmp_name'])) {
            header('Location: subir.php');
    }

    if(isset($_COOKIE['id_usuario'])) {
        
        //se transforma el chat subido en un array bidimensional

        //se definen las expresiones regulares para detectar cuando algo es un mensaje, cuando es un evento y para extraer las distintas partes de un mensaje
        $regex_mensaje="/^([1-9]|[1-2][0-9]|(3)[0-1])(\/)(([1-9])|((1)[0-2]))(\/)(\d{2})( )([0-9]|(1)[0-9]|(2)[0-3]):([0-5][0-9])( )(-)( ).+(: ).+$/s"; 
        $regex_evento="/^([1-9]|[1-2][0-9]|(3)[0-1])(\/)(([1-9])|((1)[0-2]))(\/)(\d{2})( )([0-9]|(1)[0-9]|(2)[0-3]):([0-5][0-9])( )(-)( )([^:]+)$/";
        $regex_fechahora="/([1-9]|[1-2][0-9]|(3)[0-1])(\/)(([1-9])|((1)[0-2]))(\/)(\d{2})( )([0-9]|(1)[0-9]|(2)[0-3]):([0-5][0-9])/";
        $regex_participante="/(- )([^:]+)(:)/";
        $regex_contenido="/(: ).+$/s";
        $contenido="";
       
        $archivo = fopen($_FILES['archivo']['tmp_name'], "r");
        while (!feof($archivo)) {
            $linea = fgets($archivo);
            $linea = preg_replace("/</"," lt ",$linea);
            $linea = preg_replace("/>/i"," gt ",$linea);
            //se comprueba si la linea es un mensaje
            if ( preg_match($regex_mensaje, $linea)) {
                //si lo es se comprueba si se estaba preparando un mensaje
                if ( !empty($contenido) ) {
                    //si se estaba preparando un mensaje (lo sabemos viendo si la variable contenido contiene algo) se añade al array bidimensional
                    $array_chat_subido[] = array( "fechahora" => $fechahora , "participante" => $participante , "contenido" => $contenido);
                }
                //se empieza a preparar el siguiente mensaje
                //se extrae la fecha y la hora del mensaje
                if ( preg_match($regex_fechahora, $linea, $fechahora) ) {
                    $fechahora=$fechahora[0];
                    $fechahora=date_create_from_format("j/n/y G:i", $fechahora);
                    $fechahora=date_format($fechahora,"Y-m-d H:i:s");
                }
                //se extrae el participante del mensaje    
                if ( preg_match($regex_participante, $linea, $participante) ) {
                    $participante=$participante[0];
                    $participante=substr(strval($participante),2,-1);
                }
                //se extrae el contenido del mensaje
                if ( preg_match($regex_contenido, $linea, $contenido) ) {
                    $contenido=$contenido[0];
                    $contenido=substr(strval($contenido),2);
                }
            }
            //si la línea es un evento tal como añadir un usuario a un grupo etc se ignora
            elseif ( preg_match($regex_evento, $linea) ) {
                continue;
            }
            //si la linea es la continuacion de un mensaje se añade al contenido del mensaje que se está preparando
            else {
                $contenido=$contenido." ".$linea;
            }
        }
        fclose($archivo);
        $array_chat_subido[] = array( "fechahora" => $fechahora , "participante" => $participante , "contenido" => $contenido);
        //ya tengo el array del chat que se ha subido
        
        //aqui me conecto a la base de datos
        $mysqli = new mysqli("127.0.0.1", "super", "123456", "whatsapp");

        //guardo el id de usuario el nombre del chat subido y la fecha actual en variables
        $id_usuario=$_COOKIE['id_usuario'];
        $nombre_chat=substr($_FILES['archivo']['name'],21,-4);
        $fecha_actual=date("Y-m-d H:i:s");

        //creo un array con la lista de participantes del chat que se ha subido

        for ($i=0; $i < count($array_chat_subido) ; $i++ ){
          $participantes[]=$array_chat_subido[$i]["participante"];
        }
        $participantes=array_unique($participantes);

        //si existe un chat con el nombre del chat que hemos subido obtenemos su id de chat, sus mensajes, añadimos los participantes a la tabla participantes asociandoles ese id de chat
        $cadena =  'SELECT chats.id_chat AS id_chat, mensajes.fecha AS fecha, participantes.nombre AS participante, mensajes.contenido AS contenido FROM chats JOIN mensajes ON mensajes.id_chat=chats.id_chat JOIN participantes ON participantes.id_participante=mensajes.id_participante WHERE chats.nombre="'.$nombre_chat.'" AND chats.id_usuario="'.$id_usuario.'" ORDER BY mensajes.id_mensaje';
        $consulta = $mysqli->query($cadena);
        if ( $consulta->num_rows > 0 ) {
            
            while ( $fila = $consulta->fetch_assoc() ) {
                $id_chat=$fila["id_chat"];
                
                $fecha_mensaje=$fila["fecha"];
                $participante_mensaje=$fila["participante"];
                $contenido_mensaje=$fila["contenido"];
                
                $array_chat_guardado[]=array( "fechahora" => $fecha_mensaje , "participante" => $participante_mensaje , "contenido" => $contenido_mensaje );
            }
            //se añaden los participantes del chat subido a la tabla participantes
            foreach ( $participantes as $participante ) {
                $cadena="INSERT INTO `participantes` ( `nombre`, `id_chat` ) VALUES ( '".$participante."', '".$id_chat."' )";
                $mysqli->query($cadena);
            }
            //actualizamos la fecha de subida
            $cadena="UPDATE `chats` SET `fecha_subida` = '".$fecha_actual."' WHERE `id_chat` = '".$id_chat."'";
            $mysqli->query($cadena);
            //se calcula qué mensajes se tienen que subir a la base de datos y se guardan en array_chat_subir
            $ultimo_mensaje=$array_chat_guardado[count($array_chat_guardado)-1]["fechahora"].$array_chat_guardado[count($array_chat_guardado)-1]["participante"].$array_chat_guardado[count($array_chat_guardado)-1]["contenido"];
            for ($i=0; $i<count($array_chat_subido); $i++){
              $mensaje_comparar=$array_chat_subido[$i]["fechahora"].$array_chat_subido[$i]["participante"].$array_chat_subido[$i]["contenido"];
              if ($ultimo_mensaje == $mensaje_comparar ){
                $posicion_ultimo=$i;
                break;
              } 
            }
            $array_chat_subir=array_slice($array_chat_subido,$posicion_ultimo+1);
            $array_chat_total=array_merge($array_chat_guardado,$array_chat_subir);
            //se sube la parte del chat que hay que subir
            for ($i=0; $i<count($array_chat_subir); $i++){
                $fecha_subir=$array_chat_subir[$i]["fechahora"];
                $participante_subir=$array_chat_subir[$i]["participante"];
                $contenido_subir=$array_chat_subir[$i]["contenido"];

                $cadena="INSERT INTO `mensajes` ( `id_chat`, `fecha`, `id_participante`, `contenido`) VALUES ( '".$id_chat."', '".$fecha_subir."', ( SELECT id_participante FROM participantes WHERE nombre='".$participante_subir."') , '".$contenido_subir."')";
                $mysqli->query($cadena);
            }
        }
        //si no existe ningun chat con ese nombre se suben todos los mensajes
        else {
            //primero tenemos añadir el chat a la tabla chats
            $cadena="INSERT INTO `chats` ( `id_usuario`, `nombre`, `fecha_subida` ) VALUES ( '".$id_usuario."', '".$nombre_chat."', '".$fecha_actual."' )";
            $consulta = $mysqli->query($cadena);
            //se obtiene el id_chat del chat que estamos subiendo
            $id_chat = $mysqli -> insert_id;
            //se añaden los participantes del chat subido a la tabla participantes
            foreach ( $participantes as $participante ) {
                $cadena="INSERT INTO `participantes` ( `nombre`, `id_chat` ) VALUES ( '".$participante."', '".$id_chat."' )";
                $mysqli->query($cadena);
            }
            //todos los mensajes del chat son los que hemos subido
            $array_chat_total=$array_chat_subido;
            //se añade a la base de datos todo el chat del archivo subido
            for ($i=0; $i<count($array_chat_subido); $i++) {
                $fecha_subir=$array_chat_subido[$i]["fechahora"];
                $participante_subir=$array_chat_subido[$i]["participante"];
                $contenido_subir=$array_chat_subido[$i]["contenido"];
                $cadena="INSERT INTO `mensajes` ( `id_chat`, `fecha`, `id_participante`, `contenido`) VALUES ( '".$id_chat."', '".$fecha_subir."', ( SELECT id_participante FROM participantes WHERE nombre='".$participante_subir."' AND id_chat='".$id_chat."') , '".$contenido_subir."')";
                $mysqli->query($cadena);
            }

        }
        //parte de analisis

        //calcular el numero de mensajes del chat y guardarlo
        $cadena="SELECT COUNT(*) AS mensajes_chat FROM mensajes WHERE id_chat = '".$id_chat."'";
        $consulta = $mysqli->query($cadena);
        if ( $fila = $consulta -> fetch_assoc() ){
            $n_mensajes_chat=$fila["mensajes_chat"];
        }
        $cadena="UPDATE `chats` SET `numero_mensajes` = '".$n_mensajes_chat."' WHERE `id_chat` = ".$id_chat;
        $consulta = $mysqli->query($cadena);

        //calcular el numero de participantes del chat y guardarlo 
        $cadena="SELECT COUNT(*) FROM participantes WHERE id_chat='".$id_chat."'";
        $consulta = $mysqli->query($cadena);
        if ( $fila = $consulta -> fetch_assoc() ){
            $numero_participantes=$fila["COUNT(*)"];
        }
        $cadena="UPDATE `chats` SET `numero_participantes` = '".$numero_participantes."' WHERE `id_chat` = ".$id_chat;
        $consulta = $mysqli->query($cadena);
        
        //calcular el primer mensaje y guardarlo
        $cadena="SELECT fecha FROM mensajes WHERE id_chat=".$id_chat." ORDER BY mensajes.fecha ASC LIMIT 1";
        $consulta = $mysqli->query($cadena);

        if ( $fila = $consulta -> fetch_assoc() ){
            $fecha_primer_mensaje=$fila["fecha"];
        }
        $cadena="UPDATE `chats` SET `primer_mensaje` = '".$fecha_primer_mensaje."' WHERE `id_chat` = ".$id_chat;
        $consulta = $mysqli->query($cadena);

        //calcular la fecha del ultimo mensaje y guardarla

        $cadena="SELECT fecha FROM mensajes WHERE id_chat=".$id_chat." ORDER BY mensajes.fecha DESC LIMIT 1";
        $consulta = $mysqli->query($cadena);
        if ( $fila = $consulta -> fetch_assoc() ){
            $fecha_ultimo_mensaje=$fila["fecha"];
        }
        $cadena="UPDATE `chats` SET `ultimo_mensaje` = '".$fecha_ultimo_mensaje."' WHERE `id_chat` = '".$id_chat."'";
        $consulta = $mysqli->query($cadena);

        //calcular el número de mensajes por día y guardarlo
        
        $fecha_ultimo_mensaje=new DateTime($fecha_ultimo_mensaje);
        $fecha_primer_mensaje=new DateTime($fecha_primer_mensaje);
        $diff = $fecha_ultimo_mensaje->diff($fecha_primer_mensaje);
        $dias_chat_activo=$diff->days;
        $mensajes_por_dia = ( $n_mensajes_chat / $dias_chat_activo );
        $cadena="UPDATE `chats` SET `mensajes_dia` = '".$mensajes_por_dia."' WHERE `id_chat` = ".$id_chat;
        $consulta = $mysqli->query($cadena);

        //calcular numero de mensajes por día de la semana
        $dias_semana=array("lunes", "martes", "miercoles", "jueves", "viernes", "sabado", "domingo");
        foreach ($dias_semana as $key => $value) {
            $key=$key+1;
            $cadena="SELECT COUNT(fecha) AS numero FROM `mensajes` WHERE id_chat='".$id_chat."' AND DAYOFWEEK(fecha) = ".$key;
            $consulta = $mysqli->query($cadena);
            if ( $fila = $consulta -> fetch_assoc() ){
                $numero=$fila["numero"];
            }
            $cadena="UPDATE `chats` SET `m_".$value."` = '".$numero."' WHERE `id_chat` = ".$id_chat;
            $consulta = $mysqli->query($cadena);
        }
        $dias_semana=array("lunes", "martes", "miercoles", "jueves", "viernes", "sabado", "domingo");
        //calcular el numero de mensajes por hora del día
        for ($i=0; $i < 24 ; $i++) {

            $cadena="SELECT COUNT(fecha) AS numero FROM `mensajes` WHERE id_chat=".$id_chat." AND  DATE_FORMAT(fecha, '%H') = ".$i;
            $consulta = $mysqli->query($cadena);
            if ( $fila = $consulta -> fetch_assoc() ){
                $numero=$fila["numero"];
            }
            $cadena="UPDATE `chats` SET `m".$i."` = '".$numero."' WHERE `id_chat` = ".$id_chat;
            $consulta = $mysqli->query($cadena);
        }

        //participacion numero de mensajes por usuario

        $cadena="SELECT id_participante, COUNT(*) AS numero FROM mensajes WHERE id_chat='".$id_chat."' GROUP BY id_participante";
        $consulta = $mysqli->query($cadena);
            
        while ( $fila = $consulta -> fetch_array() ) {

            $id_participante = $fila["id_participante"];
            $numero = $fila["numero"];
            $cadena="UPDATE `participantes` SET `numero_mensajes` = '".$numero."' WHERE `id_participante` = ".$id_participante;
            $consulta_update = $mysqli->query($cadena);
            
            $participacion = ceil(( $numero / $n_mensajes_chat ) * 100 );
            $cadena="UPDATE `participantes` SET `participacion` = '".$participacion."' WHERE `id_participante` = ".$id_participante;
            $update_participacion = $mysqli->query($cadena);
        }

        //guardar el número total de mensajes que tiene el usuario que está subiendo el chat en la tabla usuarios

        $cadena="SELECT COUNT(*) AS numero FROM mensajes JOIN chats ON chats.id_chat=mensajes.id_chat WHERE chats.id_usuario = '".$id_usuario."'";
        $consulta = $mysqli->query($cadena);            
        if ( $fila = $consulta -> fetch_assoc() ) {
            $numero = $fila["numero"];
            $cadena='UPDATE `usuarios` SET `total_mensajes` = "'.$numero.'" WHERE `id_usuario` = "'.$id_usuario.'"';
            $consulta_update = $mysqli->query($cadena);
        }

        //guardar la lista de meses y el número de mensajes por cada mes en la tabla meses

        $cadena="SELECT DATE_FORMAT(fecha, '%Y-%m') AS yearmonth , COUNT(mensajes.id_mensaje) AS mensajesmes FROM `mensajes` WHERE id_chat=".$id_chat." GROUP BY yearmonth";
        $consulta = $mysqli->query($cadena);
            
        while ( $fila = $consulta -> fetch_array() ) {

            $yearmonth = $fila["yearmonth"];
            $mensajesmes = $fila["mensajesmes"];

            $cadena="INSERT INTO `meses` ( `id_chat`, `mes`, `numero_mensajes_mes`) VALUES ( '".$id_chat."', '".$yearmonth."', '".$mensajesmes."' )";
            $insertmes = $mysqli->query($cadena);
            //si el mes ya existe en ese chat se actualiza el número de mensajes de ese mes
            if (!$insertmes) {
                $cadena="UPDATE `meses` SET `numero_mensajes_mes` = '".$mensajesmes."' WHERE `id_chat` = '".$id_chat."' AND `yearmonth` = '".$yearmonth."'";
                $updatemes = $mysqli->query($cadena);    
            }
        }
        //añade el id_chat del ultimo chat subido a la tabla usuarios
        $cadena='UPDATE `usuarios` SET `ultimo_chat` = "'.$id_chat.'" WHERE `id_usuario` = "'.$id_usuario.'"';
        $consulta_update = $mysqli->query($cadena);

        header('Location: chats.php');
    }
    else {
        header('Location: index.php');
    }
?>