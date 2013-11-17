<?php

require_once("../comunes/config.php");

// arrays que definen los campos POST que se tratan en el formulario y su correspondencia con campos del objeto a tratar
// automatiza su tratamiento, oculta los nombres de las bases de datos, evito duplicidades en campos POST de distintos formulario
// y marco el patrón sobre el que chequear si el campo se ajusta a lo permitido o no
$campos = array( array( "post" => "F5_C1",  "bd"  => "titular"),
                 array( "post" => "F5_C2",  "bd"  => "resumen"),
                 array( "post" => "F5_C3",  "bd"  => "textoNot"),
                 array( "post" => "F5_C4",  "bd"  => "linkRef"),
                 array( "post" => "F5_C5",  "bd"  => "fechaNot"),
                 array( "post" => "F5_C6",  "bd"  => "activa")
);

//en este se definen los campos POST obligatorios a completar
$camposRequeridos = array ("F5_C1", "F5_C2", "F5_C3", "F5_C5");

////////////////// LOGICA DE PROCESO /////////////////////////////////////////////////////////////////////////////////////////////////////////

$nivel = chequearConexion();

if ($nivel < GESTOR) { //sólo pueden subir noticias los gestores y administradores

    if ($nivel == ANONIMO) informacion(ETIQ_USUARIO_NO_AUTORIZADO,AREA_RESTRINGIDA,"",PAG_PPAL,ETIQ_LINK_PAG_PPAL);
    else informacion(ETIQ_USUARIO_NO_AUTORIZADO,AREA_RESTRINGIDA,"",PAG_AREA_PERSONAL,ETIQ_LINK_AREA_PERSONAL);

} else { //en esta parte se lleva a cabo el tratamiento para usuarios conectados, que trata la modificación o sólo visualización de los datos



         $error="";
         $idNoticia = "";
         $noticiaBD = "";
         $idUsuNot = "";


         //1. comprobamos si hay peticion explícita y que el valor de idNoticia es válido
         if (GET("noticia")) {
             if (!$error=chequearCampo("idNoticia", GET("noticia"))) $idNoticia = GET("noticia");
         } else {
             $idNoticia = 0; //en otro caso, se pretende registrar una noticia nueva
         }

         if ((!$error)&&($idNoticia)) if(!$noticiaBD = Noticia::obtenerPorIdNoticia($idNoticia)) $error=ETIQ_NOTICIA_NO_EXISTE;

         //2. si se ha hecho correctamente la petición, decidimos el modo de operación e inicializamos variables y sesiones si es pertinente
         if (!$error) {
              if ($noticiaBD) {
                  $idUsuNot = $noticiaBD->obtenerCampo("idUsuario");
                  if ((SESSION_CAMPO("usuario","idUsuario"))==$idUsuNot)
                        $modo = MODI;  //si no hay error y hay idNoticia que pertenece a la empresa del usuario conectado, se puede modificar
                  else {
                        if ($nivel >= GESTOR) $modo = MDTU; //sólo  ADMINISTRADORES pueden ver noticias de empresas que no son la suya y activarla o desactivarla
                        else $error = ETIQ_USUARIO_NO_AUTORIZADO; //por eso si es sólo premium y lo intenta, no está autorizado
                  }
                  if ((SESSION("noticia")!=ETIQ_OPER_REALIZADA)AND((!SESSION("noticia"))OR($idNoticia != SESSION_CAMPO("noticia","idNoticia")))) {
                      $_SESSION["noticia"] = $noticiaBD;
                  }
              } else {
                  $idUsuNot = SESSION_CAMPO("usuario","idUsuario");
                  $modo = ALTA; //en otro caso estamos ante el alta de una nueva noticia
              }
         }

                  //esta señal impide que se repita la operación por refrescar la página cuando se da el mensaje de información
         if (SESSION("noticia")==ETIQ_OPER_REALIZADA) {
             $error = ETIQ_OPER_REALIZADA;
         }

         //4. si no hay errores, procedemos a realizar su tratamiento
         if (!$error) {

      	                   if (POST("enviar")) {   //tratamiento general que procede al alta o actualización según el modo
                               $errores = array();
                               $camposQueFaltan = array();
                               comprobarCampos($campos,$camposRequeridos,$camposQueFaltan,$errores,$modo);
                               if ($errores) mostrarFormNoticia($campos,$camposQueFaltan,$errores,$modo,$nivel);
                               else {
                                    $nuevaNoticia = crearNoticia($campos,$idUsuNot,$noticiaBD,$modo,$nivel);
                                    if (($modo != ALTA) AND (sonIguales($nuevaNoticia,$noticiaBD,$campos))) {
                                         $errores[]=ETIQ_REG_IGUALES;
                                         mostrarFormNoticia($campos,array(),$errores,$modo,$nivel);
                                    } else {  if ($modo == ALTA) {
                                                  $nuevaNoticia->grabarEnBD();
                                                  $nuevaNoticia = Noticia::obtenerUltimaNoticia($idUsuNot);
                                               } else $nuevaNoticia->actualizarEnBD();
                                               $idNoticia = $nuevaNoticia->obtenerCampo("idNoticia");
                                               $_SESSION["noticia"]=ETIQ_OPER_REALIZADA; //esto indicará que se hizo el alta e impedirá que se repita por F5
                                               if ($modo == ALTA) informacion(ETIQ_ALTA_NOT_OK,ENHORABUENA,"",PAG_AREA_PERSONAL,ETIQ_LINK_AREA_PERSONAL);
                                               else informacion(ETIQ_ACTU_NOT_OK,INFORMACION,"",PAG_AREA_PERSONAL,ETIQ_LINK_AREA_PERSONAL);
                                   }           

                               }

                    } elseif (POST("activar")) { //si sólo se pretende activar o desactivar la noticia
                              if ($modo == MDTU) cargarCamposPOST($campos,SESSION("noticia"));
                              if (POST("F5_C6")) {
                                  $_POST["F5_C6"] = 0;
                                  SESSION("noticia")->desactivarNoticia();
                              } else {
                                  $_POST["F5_C6"] = 1;
                                  SESSION("noticia")->activarNoticia();
                              }
                              $nuevaNoticia = crearNoticia($campos,$idUsuNot,$noticiaBD,$modo,$nivel);
                              $_SESSION["noticia"]=$nuevaNoticia;
                              mostrarFormNoticia($campos,array(),array(),$modo,$nivel);

                    } elseif ((POST("borrar")) && ($modo==ALTA)) { //limpia los campos del formulario
                              inicializarCamposPOST($campos);
                              unset($_SESSION["noticia"]);
                              mostrarFormNoticia($campos,array(),array(),$modo,$nivel);

      	            } elseif ((POST("borrar")) && ($modo==MODI) && (POST("borrar")==ETIQ_BT_CONFIRMAR_BAJA_NOT)) {  //se ha confirmado borrar el usuario
                              SESSION("noticia")->borrarDeBD(SESSION_CAMPO("noticia","idNoticia"));
                              unset($_SESSION["noticia"]);
	                      if (SESSION("listNoticias"))
                                  informacion(ETIQ_NOTICIA_ELIMINADA,INFORMACION,"",PAG_LIST_NOT,ETIQ_LINK_VOLVER_LIST,PAG_AREA_PERSONAL,ETIQ_LINK_AREA_PERSONAL);
                              else
                                  informacion(ETIQ_NOTICIA_ELIMINADA,INFORMACION,"",PAG_AREA_PERSONAL,ETIQ_LINK_AREA_PERSONAL);

      	            } elseif ((POST("borrar")) && ($modo==MODI)) {  //si se pretende eliminar la noticia, antes se pide confirmacion
                               $errores = array();
                               $camposQueFaltan = array();
 	                       $errores[]=ETIQ_AVISO_BORRAR_NOT;
                               cargarCamposPOST($campos,SESSION("noticia"));
	                       mostrarFormNoticia($campos,$camposQueFaltan,$errores,BAJA,$nivel);

      	             } elseif (POST("usuario")) {  //queremos ir a ver los datos del usuario asociado a la empresa
                               redirigir(PAG_USUARIOS."?usuario=".$idUsuNot);

                    } else {   if (($modo!=ALTA) AND (SESSION("noticia"))) cargarCamposPOST($campos,SESSION("noticia"));  //se envían los campos al formulario nada más
                               mostrarFormNoticia($campos,array(),array(),$modo,$nivel);
                    }

         } else informacion($error,ERROR,"",PAG_AREA_PERSONAL,ETIQ_LINK_AREA_PERSONAL);

}

/////////////////// FUNCIONES /////////////////////////////////////////////////////////////////////////////////////////////


//// FUNCIONES DE CHEQUEO PROPIAS

function comprobarFechaNot(&$camposQueFaltan, &$errores) {
        $d = substr(POST("F5_C5"),0,2);
        $m = substr(POST("F5_C5"),3,2);
        $a = substr(POST("F5_C5"),6,4);
        if (!checkdate($m,$d,$a)) {
	   $errores[] = ETIQ_FECHA_NO_VALIDA;
       	   $camposQueFaltan[] = "F5_C5";
       	}
}



//////////// FUNCIONES DE TRATAMIENTO


function crearNoticia($campos, $idUsuario, $noticiaBD, $modo, $nivel) {

        $propiedades = array();
        //en el modo MDTU se deshabilitan los campos POST y no se devuelven, salvo el único permitido, activa...
        if ($modo==MDTU) foreach($campos as $campo) $propiedades[$campo["bd"]]=$noticiaBD->obtenerCampo($campo["bd"]);
                   else  foreach($campos as $campo) $propiedades[$campo["bd"]]=POST($campo["post"]);
        if ($modo == ALTA) {
            $propiedades["idNoticia"]="";
            $propiedades["idUsuario"]=$idUsuario;
        } else {
            $propiedades["activa"] = POST("F5_C6"); //por si se ha modificado el campo "activa"
            $propiedades["idNoticia"]   = $noticiaBD->obtenerCampo("idNoticia");
            $propiedades["idUsuario"] = $noticiaBD->obtenerCampo("idUsuario");
        }
        return new Noticia($propiedades);

}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////

// y esta función es la que muestra el formulario que compone la ficha de un usuario
function mostrarFormNoticia($campos, $camposQueFaltan, $errores, $modo=ALTA, $nivel=ANONIMO) {


//primero damos de alta la plantilla y rellenamos títulos y mensajes en la cabecera
        $smarty = nuevaPlantilla();

        if ($modo==ALTA) $tituloPagina = ETIQ_TIT_REG_NOT;
                    else $tituloPagina = ETIQ_TIT_MOD_NOT;

        $smarty->assign('tituloPagina',$tituloPagina);

        if (!$errores) $errores[] = ($modo==ALTA) ? ETIQ_SUBTIT_INTRO_DATOS : "";
        else $smarty->assign('class_mensajes',CLAS_ERROR);

        $smarty->assign('mensajes',$errores);

        $smarty->display(TPL_CABECERA);

        $smarty->assign('opciones_F5_C6', array("1" => "Activada"));
        
        if ($modo == ALTA) $_POST["F5_C6"]=1;

        if (($modo == ALTA) && (!(POST("F5_C5")))) {
            $_POST["F5_C5"]=date("d")."/".date("m")."/".date("Y");
        }

//posteriormente rellenamos todas las variables asociadas a campos POST.
//y su clase a "error" si resulta que el campo está entre los que faltan
        foreach ($campos as $campo) {
          	 $smarty->assign($campo["post"],POST($campo["post"]));
          	 $smarty->assign('class_'.$campo["post"],comprobarCampo($campo["post"],$camposQueFaltan));
        }

////////////////////////////en función del modo y el nivel, establecemos los campos y botones a mostrar

//y ahora los botones y bloqueo de campos...



              if ($modo==ALTA) {  $smarty->assign('mostrar_Enviar',true);
                                  $smarty->assign('enviar',ETIQ_BT_ALTA_NOT);
                                  $smarty->assign('mostrar_Borrar',true);
                                  $smarty->assign('borrar',ETIQ_BT_LIMPIAR);

        } elseif ($modo==MODI) {  $smarty->assign('mostrar_Enviar',true);
                                  $smarty->assign('enviar',ETIQ_BT_ACTU_NOT);
                                  $smarty->assign('mostrar_Borrar',true);
                                  $smarty->assign('borrar',ETIQ_BT_BAJA_NOT);
                                  if ($nivel > GESTOR) {
                                  $smarty->assign('mostrar_Activar',true);
                                  if (POST("F5_C6")) $smarty->assign('activar',ETIQ_BT_DESACTIVAR_NOT);
                                  else $smarty->assign('activar',ETIQ_BT_ACTIVAR_NOT);
                                  }
                                  $smarty->assign('mostrar_Usuario',true);
                                  $smarty->assign('usuario',ETIQ_BT_VER_USU);

        } elseif ($modo==MDTU) {  foreach ($campos as $campo) $smarty->assign('bloquear_'.$campo["post"],'disabled="disabled"');
                                  $smarty->assign('mostrar_Activar',true);
                                  if (POST("F5_C6")) $smarty->assign('activar',ETIQ_BT_DESACTIVAR_NOT);
                                  else $smarty->assign('activar',ETIQ_BT_ACTIVAR_NOT);
                                  $smarty->assign('mostrar_Usuario',true);
                                  $smarty->assign('usuario',ETIQ_BT_VER_USU);

        } elseif ($modo==BAJA) {  $smarty->assign('mostrar_Borrar',true); //modo especial transitorio cuando en MODI lo que se pide es eliminar el usuario. Pide confirmación
                                  $smarty->assign('borrar',ETIQ_BT_CONFIRMAR_BAJA_NOT);
                                  $smarty->assign('mostrar_Cancelar',true);
                                  $smarty->assign('cancelar',ETIQ_BT_CANCELAR);
                                  foreach ($campos as $campo) $smarty->assign('bloquear_'.$campo["post"],'disabled="disabled"');
        // cualquier otro caso sería visualización, por lo que se bloquea todo y no se muestra un botón. A no ser que haya un mal modo, a este "ELSE" no llegará
        } else  foreach ($campos as $campo) $smarty->assign('bloquear_'.$campo["post"],'disabled="disabled"');

        if ($modo == ALTA) $smarty->assign('action',completarURL(PAG_NOTICIAS));
                     else  $smarty->assign('action',completarURL(PAG_NOTICIAS."?noticia=".SESSION_CAMPO("noticia","idNoticia")));

	 $smarty->display(TPL_FICHA_NOT);

 	 if ($nivel == ANONIMO) {
     	     $smarty->assign('linkPie',completarURL(PAG_USUARIOS));
 	     $smarty->assign('textoLinkPie',ETIQ_LINK_VOLVER);
	 } else {
             if (SESSION("listNotUsu")) {
       	         $smarty->assign('linkAlt',completarURL(PAG_AREA_PERSONAL));
 	         $smarty->assign('textoLinkAlt',ETIQ_LINK_AREA_PERSONAL);
                 $smarty->assign('linkPie',completarURL(PAG_LIST_NOT_USU));
  	         $smarty->assign('textoLinkPie',ETIQ_LINK_VOLVER_LIST);
             }elseif (SESSION("listNoticias")) {
       	         $smarty->assign('linkAlt',completarURL(PAG_AREA_PERSONAL));
 	         $smarty->assign('textoLinkAlt',ETIQ_LINK_AREA_PERSONAL);
                 $smarty->assign('linkPie',completarURL(PAG_LIST_NOT));
  	         $smarty->assign('textoLinkPie',ETIQ_LINK_VOLVER_LIST);
             } else {
     	         $smarty->assign('linkPie',completarURL(PAG_AREA_PERSONAL));
 	         $smarty->assign('textoLinkPie',ETIQ_LINK_AREA_PERSONAL);
             }
  	 }
	 $smarty->display(TPL_PIE);
}
?>