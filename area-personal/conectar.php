<?php

require_once("../comunes/config.php");

// arrays que definen los campos POST que se tratan en el formulario y su correspondencia con campos del objeto a tratar
// automatiza su tratamiento, oculta los nombres de las bases de datos, evito duplicidades en campos POST de distintos formulario
// y marco el patrón sobre el que chequear si el campo se ajusta a lo permitido o no
$campos = array( array( "post" => "F0_C1",  "bd" => "usuario"),
                 array( "post" => "F0_C2",  "bd" => "password"));

//en este se definen los campos POST obligatorios a completar
$camposRequeridos = array( "F0_C1", "F0_C2" );

////////////////// LOGICA DE PROCESO /////////////////////////////////////////////////////////////////////////////////////////////////////////

$nivel = chequearConexion();

//esta primera parte es para cuando se llama mediante una llamada POST con ese parámetro (sin necesidad de informar nada más)
if (POST("soloChequear")) {

  if ($nivel == ANONIMO) {

     $_POST["F0_C1"]=POST("usuario");
     $_POST["F0_C2"]=POST("password");
     $camposQueFaltan=array();
     $errores=array();
     conectar($campos, $camposRequeridos, $camposQueFaltan, $errores);
     if ($errores) echo "{resultado: \"error\",  mensaje: \"$errores[0]\"}";

     else  echo "{resultado: \"ok\",  mensaje: \"".SESSION_CAMPO("usuario","nombre")."\"}";

  } else echo "{resultado: \"conectado\",  mensaje: \"".ETIQ_USUARIO_YA_CONEX."\"}";


// y aquí es la lógica de tratamiento cuando rellenamos el formulario CONECTAR.TPL
} else {

  if ($nivel == ANONIMO) {

          if ((POST("enviar")) OR

//esta añadido es sólo para dar la comodidad de acceso a pruebas
	      (isset($_GET["pruebasocio"])) OR
	      (isset($_GET["pruebagestor"])) ) {

	        if (isset($_GET["pruebasocio"])) {
	            $_POST["F0_C1"]="francisco";
	            $_POST["F0_C2"]="f4615eb6";
	        }

	        if (isset($_GET["pruebagestor"])) {
	            $_POST["F0_C1"]="jesus";
	            $_POST["F0_C2"]="e7572075";
	        }

///////////////////////////////////////////////////////////////////77
                $camposQueFaltan=array();
                $errores=array();
                conectar($campos, $camposRequeridos, $camposQueFaltan, $errores);
               	if ($errores) { inicializarCamposPOST($campos);
                                mostrarFormulario($campos, array(), $errores);
        	} else informacion("Bienvenido, ".SESSION_CAMPO("usuario","nombre")."!",ENHORABUENA,ETIQ_CONEX_OK,PAG_AREA_PERSONAL,ETIQ_LINK_IR_AREA_PERSONAL,PAG_PPAL,ETIQ_PAG_PPAL);

          } else {
	        mostrarFormulario($campos, array(), array());
          }


  } else informacion(ETIQ_USUARIO_YA_CONEX,INFORMACION,"",PAG_AREA_PERSONAL,ETIQ_LINK_AREA_PERSONAL,PAG_DECONEX,ETIQ_LINK_DECONEX);

}

/////////////////// FUNCIONES /////////////////////////////////////////////////////////////////////////////////////////////

function conectar($campos, $camposRequeridos, &$camposQueFaltan, &$errores) {

        comprobarCampos($campos,$camposRequeridos,$camposQueFaltan,$errores);
        if ($errores) {
                if ($errores[0]==ETIQ_COMPLETAR_CAMPOS) {$errores = ""; $errores[] = ETIQ_COMPLETAR_USU_PASS;}
                                                  else  {$errores = ""; $errores[] = ETIQ_DATOS_CONEX_INCORRECTOS;}
        } else { $usuario = crearUsuario($campos);
	 	 if (!$usuarioAutentificado = $usuario->autentificar())
                      $errores[] = ETIQ_DATOS_CONEX_INCORRECTOS;
		 else {
                      $_SESSION["usuario"]=$usuarioAutentificado;
                }
        }

}

function crearUsuario($campos) {

        $propiedades = array();
        foreach($campos as $campo) $propiedades[$campo["bd"]]=POST($campo["post"]);
        return  new Usuario($propiedades);
}

function mostrarFormulario($campos, $camposQueFaltan, $errores) {
  
  
	 $smarty = nuevaPlantilla();

         $smarty->assign('tituloPagina',ETIQ_TIT_PAG_CONEX);
         
         if (!$errores) $errores[]= ETIQ_SUBTIT_INTRO_CONEX;
         else $smarty->assign('class_mensajes',CLAS_ERROR);

	 $smarty->assign('mensajes',$errores);

 	 $smarty->display(TPL_CABECERA);

//posteriormente rellenamos todas las variables asociadas a campos POST.
//y su clase a "error" si resulta que el campo está entre los que faltan
         foreach ($campos as $campo) {
          	 $smarty->assign('class_'.$campo["post"],comprobarCampo($campo["post"],$camposQueFaltan));
         }

         $smarty->assign('enviar',ETIQ_BT_CONECTAR);
       	 $smarty->assign('action',completarURL(PAG_CONEX));

	 $smarty->display(TPL_CONECTAR);

       	 $smarty->assign('linkAlt',completarURL(PAG_RECORDAR));
 	 $smarty->assign('textoLinkAlt',ETIQ_LINK_OLVIDO);
  	 $smarty->assign('linkPie',completarURL(PAG_REG_PPAL));
 	 $smarty->assign('textoLinkPie',ETIQ_LINK_REG);
	 $smarty->display(TPL_PIE);

}

?>
