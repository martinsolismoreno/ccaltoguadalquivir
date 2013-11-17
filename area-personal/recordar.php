<?php

require_once("../comunes/config.php");
require_once("../phpmailer/class.phpmailer.php");
require_once("../phpmailer/class.smtp.php");

// arrays que definen los campos POST que se tratan en el formulario y su correspondencia con campos del objeto a tratar
// automatiza su tratamiento, oculta los nombres de las bases de datos, evito duplicidades en campos POST de distintos formulario
// y marco el patrón sobre el que chequear si el campo se ajusta a lo permitido o no
$campos = array( array( "post" => "F6_C1",  "bd" => "email"));

//en este se definen los campos POST obligatorios a completar
$camposRequeridos = array( "F6_C1" );

////////////////// LOGICA DE PROCESO /////////////////////////////////////////////////////////////////////////////////////////////////////////

$nivel = chequearConexion();

if ($nivel == ANONIMO) {
  
    if (SESSION("recordar")) redirigir(PAG_PPAL);
    else {
        if (POST("enviar")||(POST("F6_C1"))) {
                $camposQueFaltan=array();
                $errores=array();
                recordar($campos, $camposRequeridos, $camposQueFaltan, $errores);
                if (($errores) AND ($errores[0]==ETIQ_CORREO_NO_ENVIADO)) informacion(ETIQ_CORREO_NO_ENVIADO,ERROR);
       	    elseif ($errores) mostrarFormulario($campos, array(), $errores);
                else informacion(ETIQ_ENVIO_OK,ENHORABUENA,"El correo se ha enviado correctamente");
    
        } else  mostrarFormulario($campos, array(), array());
    }


} else informacion(ETIQ_USUARIO_YA_CONEX,INFORMACION,"",PAG_AREA_PERSONAL,ETIQ_LINK_AREA_PERSONAL,PAG_DECONEX,ETIQ_LINK_DECONEX);


/////////////////// FUNCIONES /////////////////////////////////////////////////////////////////////////////////////////////

function recordar($campos, $camposRequeridos, &$camposQueFaltan, &$errores) {

        comprobarCampos($campos,$camposRequeridos,$camposQueFaltan,$errores);
        if (!$errores) {
	     if ($usuario=Usuario::obtenerPorEmail(POST("F6_C1"))) {

//hago uso de esta clase que he visto por internet, ya que la función mail() de php requería un servidor local y bajo windows 
//no iba... he visto más fácil demostrar una posible solución de esta manera, aunque desde luego muy profesional no es
//el caso es que sabía que el servidor de terra permite hacer envíos, así que por eso abrí una cuenta para este ejemplo,
//que por supuesto no es una solución profesional y es suceptible de mucha mejora...

	         $nombre = $usuario->obtenerCampo("nombre")." ".$usuario->obtenerCampo("apellido1")." ".$usuario->obtenerCampo("apellido2");
                 $mensaje = "Estimado, ".$nombre.".<br><br>";
                 $mensaje.= "De acuerdo a su petición de recordatorio de datos de acceso a nuestro portal,<br>";
                 $mensaje.= 'le indicamos que su nombre de <em>usuario</em> es: <strong>'.$usuario->obtenerCampo("usuario")."</strong>,<br>";
		 //por supuesto que habría que crear un clave mejor aleatoriamente y con alguna medida de seguridad mas...
                 $clave =  substr(md5(microtime()),1,8);
                 $mensaje.= 'y que se le ha generado de forma temporal una nueva <em>contraseña</em> de acceso: <strong>'.$clave.'</strong>.';
                 $mensaje.= "<br><br><br>Por favor, ingrese en su cuenta y modifique la contraseña que se le ha habilitado temporalmente.";
                 $mensaje.= "<br><br><br>Atentamente,";
                 $mensaje.= "<br><br>Centro Comercial Alto Guadalquivir<br>";

		 $mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
 
		 $mail->IsSMTP(); // telling the class to use SMTP
 
		 try {
		     	$mail->Host       = "ccaltoguadalquivir.gmail.com"; // SMTP server
			//$mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
			$mail->SMTPAuth   = true;                  // enable SMTP authentication
			$mail->SMTPSecure = "tls";                 // sets the prefix to the servier
			$mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
			$mail->Port       = 587;                   // set the SMTP port for the GMAIL server
			$mail->Username   = "ccaltoguadalquivir@gmail.com";  // GMAIL username
			$mail->Password   = "T4qPs59a";            // GMAIL password
			$mail->AddReplyTo('ccaltoguadalquivir@gmail.com', 'CC Alto Guadalquivir');
			$mail->AddAddress($usuario->obtenerCampo("email"), $nombre);
			$mail->SetFrom('ccaltoguadalquivir@gmail.com', 'CC Alto Guadalquivir');
			$mail->AddReplyTo('ccaltoguadalquivir@gmail.com', 'CC Alto Guadalquivir');
			$mail->WordWrap = 50; // Largo de las lineas
		        $mail->IsHTML(true); // Podemos incluir tags html
			$mail->Subject = "Acceso a Centro Comercial Alto Guadalquivir";
			$mail->AltBody = 'Para ver el mensaje, por favor, use un cliente compatible con HTML.'; 
					// optional - MsgHTML will create an  alternate automatically
	                $mail->Body = $mensaje;
			$mail->MsgHTML($mensaje);
			//$mail->AddAttachment('images/phpmailer.gif');      // attachment
			//$mail->AddAttachment('images/phpmailer_mini.gif'); // attachment
			$mail->Send();
			$usuario->actualizarPassword($clave);
			} catch (phpmailerException $e) {
			      $errores[] = ETIQ_CORREO_NO_ENVIADO;//."<br></br>".$e->errorMessage(); //Pretty error messages from PHPMailer
			} catch (Exception $e) {
			      $errores[] = ETIQ_CORREO_NO_ENVIADO;//."<br></br>".$e->getMessage(); //Boring error messages from anything else!
			}

                 $_SESSION["recordar"]=true;

             } else {

                 $errores[] = ETIQ_CORREO_NO_EXISTE;

	     }
        }

}


function mostrarFormulario($campos, $camposQueFaltan, $errores) {


	 $smarty = nuevaPlantilla();

         $smarty->assign('tituloPagina',ETIQ_TIT_PAG_RECORDAR);

         if (!$errores) $errores[]= ETIQ_SUBTIT_INTRO_RECORDAR;
         else $smarty->assign('class_mensajes',CLAS_ERROR);

	 $smarty->assign('mensajes',$errores);

 	 $smarty->display(TPL_CABECERA);

//posteriormente rellenamos todas las variables asociadas a campos POST.
//y su clase a "error" si resulta que el campo está entre los que faltan
         foreach ($campos as $campo) {
                 $smarty->assign($campo["post"],POST($campo["post"]));
          	 $smarty->assign('class_'.$campo["post"],comprobarCampo($campo["post"],$camposQueFaltan));
         }

         $smarty->assign('enviar',ETIQ_BT_ENVIAR_CORREO);
       	 $smarty->assign('action',completarURL(PAG_RECORDAR));

	 $smarty->display(TPL_RECORDAR);

    	 $smarty->assign('linkAlt',completarURL(PAG_CONEX));
 	 $smarty->assign('textoLinkAlt',ETIQ_BT_CONECTAR);
  	 $smarty->assign('linkPie',completarURL(PAG_REG_PPAL));
 	 $smarty->assign('textoLinkPie',ETIQ_LINK_REG);
	 $smarty->display(TPL_PIE);

}

?>