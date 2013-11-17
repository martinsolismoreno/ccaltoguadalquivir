<?php

require_once("../comunes/config.php");

// arrays que definen los campos POST que se tratan en el formulario y su correspondencia con campos del objeto a tratar
// automatiza su tratamiento, oculta los nombres de las bases de datos, evito duplicidades en campos POST de distintos formulario
// y marco el patrón sobre el que chequear si el campo se ajusta a lo permitido o no
$campos = array( array( "post" => "F4_C1",  "bd"  => "oferta"),
                 array( "post" => "F4_C2",  "bd"  => "textoOferta"),
                 array( "post" => "F4_C3",  "bd"  => "textoCond"),
                 array( "post" => "F4_C4",  "bd"  => "fechaIni"),
                 array( "post" => "F4_C5",  "bd"  => "fechaFin"),
                 array( "post" => "F4_C6",  "bd"  => "activa")
);

//en este se definen los campos POST obligatorios a completar
$camposRequeridos = array ("F4_C1", "F4_C2", "F4_C3");

////////////////// LOGICA DE PROCESO /////////////////////////////////////////////////////////////////////////////////////////////////////////

$nivel = chequearConexion();

if ($nivel < PREMIUM) { //sólo pueden subir ofertas usuarios de nivel PREMIUM, GESTORES (que son por defecto PREMIUM) y el ADMINISTRADOR de la empresa genérica del portal

    if ($nivel == ANONIMO) informacion(ETIQ_USUARIO_NO_AUTORIZADO,AREA_RESTRINGIDA,"",PAG_PPAL,ETIQ_LINK_PAG_PPAL);
    else informacion(ETIQ_USUARIO_NO_AUTORIZADO,AREA_RESTRINGIDA,"",PAG_AREA_PERSONAL,ETIQ_LINK_AREA_PERSONAL);

} else { //en esta parte se lleva a cabo el tratamiento para usuarios conectados, que trata la modificación o sólo visualización de los datos



         $error="";
         $idOferta = "";
         $ofertaBD = "";
         $idEmpOfe = "";
         $idUsuOfe = "";


         //1. comprobamos si hay peticion explícita y que el valor de idOferta es válido
         if (GET("oferta")) {
             if (!$error=chequearCampo("idOferta", GET("oferta"))) $idOferta = GET("oferta");
         } else {
             $idOferta = 0; //en otro caso, se pretende registrar una oferta nueva
         }

         if ((!$error)&&($idOferta)) if(!$ofertaBD = Oferta::obtenerPorIdOferta($idOferta)) $error=ETIQ_OFERTA_NO_EXISTE;

         //2. si se ha hecho correctamente la petición, decidimos el modo de operación e inicializamos variables y sesiones si es pertinente
         if (!$error) {
              if ($ofertaBD) {
                  $idEmpOfe = $ofertaBD->obtenerCampo("idEmpresa");
                  $empresa = Empresa::obtenerPorIdEmpresa($idEmpOfe);
                  $idUsuOfe = $empresa->obtenerCampo("idUsuario");
                  if ((SESSION_CAMPO("usuario","idUsuario"))==$idUsuOfe)
                        $modo = MODI;  //si no hay error y hay idOferta que pertenece a la empresa del usuario conectado, se puede modificar
                  else {
                        if ($nivel >= GESTOR) $modo = MDTU; //sólo GESTORES Y ADMINISTRADORES pueden ver ofertas de empresas que no son la suya y activarla o desactivarla
                        else $error = ETIQ_USUARIO_NO_AUTORIZADO; //por eso si es sólo premium y lo intenta, no está autorizado
                  }
                  if ((SESSION("oferta")!=ETIQ_OPER_REALIZADA)AND((!SESSION("oferta"))OR($idOferta != SESSION_CAMPO("oferta","idOferta")))) {
                      $_SESSION["oferta"] = $ofertaBD;
                      $_SESSION["imgOferta"] = "";
                      $_SESSION["archOferta"] = "";
                  }
              } else {                                                                                         
                  $idUsuOfe = SESSION_CAMPO("usuario","idUsuario");
                  $empresa = Empresa::obtenerPorIdUsuario($idUsuOfe);
                  $idEmpOfe = $empresa->ObtenerCampo("idEmpresa");
                  $modo = ALTA; //en otro caso estamos ante el alta de una nueva oferta
              }
         }

                  //esta señal impide que se repita la operación por refrescar la página cuando se da el mensaje de información
         if (SESSION("oferta")==ETIQ_OPER_REALIZADA) {
             $error = ETIQ_OPER_REALIZADA;
         }

         //4. si no hay errores, procedemos a realizar su tratamiento
         if (!$error) {

      	                   if (POST("enviar")) {   //tratamiento general que procede al alta o actualización según el modo
                               $_SESSION["imgOferta"]=$_POST["imgOferta"];
                               $errores = array();
                               $camposQueFaltan = array();
                               comprobarCampos($campos,$camposRequeridos,$camposQueFaltan,$errores,$modo);
                               comprobarImagenOferta($errores,$modo);
                               if ($errores) mostrarFormOferta($campos,$camposQueFaltan,$errores,$modo,$nivel);
                               else {
                                    $nuevaOferta = crearOferta($campos,$idEmpOfe,$ofertaBD,$modo,$nivel);
                                    if (($modo != ALTA) AND (sonIguales($nuevaOferta,$ofertaBD,$campos)&&(!SESSION("archOferta")))) {
                                         $errores[]=ETIQ_REG_IGUALES;
                                         mostrarFormOferta($campos,array(),$errores,$modo,$nivel);
                                    } else {  if ($modo == ALTA) {
                                                  $nuevaOferta->grabarEnBD();
                                                  $nuevaOferta = Oferta::obtenerUltimaOferta($idEmpOfe);
                                               } else $nuevaOferta->actualizarEnBD();
                                               $idOferta = $nuevaOferta->obtenerCampo("idOferta");
                                               $error = salvarImagen($idOferta);
                                               if (($error)&&($modo == ALTA)) $nuevaOferta->desactivarFichero();
                                               else {
                                                   $archivo = RUTA_ABS.DIR_ARCH_OFE.PREFIJO_ARCH_OFE.$idOferta;
                      	                           if ((file_exists($archivo.".gif")) OR (file_exists($archivo.".jpg")))
                      	                                $nuevaOferta->activarFichero();
                      	                           else
                      	                                $nuevaOferta->desactivarFichero();
                                               }
                                               unset($_SESSION["archOferta"]);
                                               unset($_SESSION["imgOferta"]);
                                               $_SESSION["oferta"]=ETIQ_OPER_REALIZADA; //esto indicará que se hizo el alta e impedirá que se repita por F5
                                               if ($error) informacion($error,ERROR,"",PAG_OFERTAS."?oferta=$idOferta",ETIQ_LINK_VOLVER,PAG_AREA_PERSONAL,ETIQ_LINK_AREA_PERSONAL);
                                               elseif ($modo == ALTA) informacion(ETIQ_ALTA_OFE_OK,ENHORABUENA,"",PAG_AREA_PERSONAL,ETIQ_LINK_AREA_PERSONAL);
                                               else informacion(ETIQ_ACTU_OFE_OK,INFORMACION,"",PAG_AREA_PERSONAL,ETIQ_LINK_AREA_PERSONAL);
                                   }           

                               }

                    } elseif (POST("activar")) { //si sólo se pretende activar o desactivar la oferta
                              if ($modo == MDTU) cargarCamposPOST($campos,SESSION("oferta"));
                              if (POST("F4_C6")) {
                                  $_POST["F4_C6"] = 0;
                                  SESSION("oferta")->desactivarOferta();
                              } else {
                                  $_POST["F4_C6"] = 1;
                                  SESSION("oferta")->activarOferta();
                              }
                              $nuevaOferta = crearOferta($campos,$idEmpOfe,$ofertaBD,$modo,$nivel);
                              $_SESSION["oferta"]=$nuevaOferta;
                              mostrarFormOferta($campos,array(),array(),$modo,$nivel);

                    } elseif ((POST("borrar")) && ($modo==ALTA)) { //limpia los campos del formulario
                              inicializarCamposPOST($campos);
                              unset($_SESSION["oferta"]);
		              unset($_SESSION["imgOferta"]);
		              unset($_SESSION["archOferta"]);
                              mostrarFormOferta($campos,array(),array(),$modo,$nivel);

      	            } elseif ((POST("borrar")) && ($modo==MODI) && (POST("borrar")==ETIQ_BT_CONFIRMAR_BAJA_OFE)) {
                              $archivo = RUTA_ABS.DIR_ARCH_OFE.PREFIJO_ARCH_OFE.SESSION_CAMPO("oferta","idOferta");
 	                      if (file_exists($archivo.".gif")) unlink($archivo.".gif");
	                      if (file_exists($archivo.".jpg")) unlink($archivo.".jpg");
                              SESSION("oferta")->borrarDeBD(SESSION_CAMPO("oferta","idOferta"));
                              unset($_SESSION["oferta"]);
		              unset($_SESSION["imgOferta"]);
		              unset($_SESSION["archOferta"]);
	                      if (SESSION("listOfertas"))
                                  informacion(ETIQ_OFERTA_ELIMINADA,INFORMACION,"",PAG_LIST_OFE,ETIQ_LINK_VOLVER_LIST,PAG_AREA_PERSONAL,ETIQ_LINK_AREA_PERSONAL);
                              else
                                  informacion(ETIQ_OFERTA_ELIMINADA,INFORMACION,"",PAG_AREA_PERSONAL,ETIQ_LINK_AREA_PERSONAL);

      	            } elseif ((POST("borrar")) && ($modo==MODI)) {  //si se pretende eliminar la oferta, antes se pide confirmacion
                               $errores = array();
                               $camposQueFaltan = array();
 	                       $errores[]=ETIQ_AVISO_BORRAR_OFE;
                               cargarCamposPOST($campos,SESSION("oferta"));
	                       mostrarFormOferta($campos,$camposQueFaltan,$errores,BAJA,$nivel);
	                       
      	             } elseif (POST("empresa")) {  //queremos ir a ver los datos del usuario asociado a la empresa
                               redirigir(PAG_EMPRESAS."?usuario=".$idUsuOfe);

                    } else {   if (($modo!=ALTA) AND (SESSION("oferta"))) cargarCamposPOST($campos,SESSION("oferta"));  //se envían los campos al formulario nada más
                               mostrarFormOferta($campos,array(),array(),$modo,$nivel);
                    }

         } else informacion($error,ERROR,"",PAG_AREA_PERSONAL,ETIQ_LINK_AREA_PERSONAL);

}

/////////////////// FUNCIONES /////////////////////////////////////////////////////////////////////////////////////////////


//// FUNCIONES DE CHEQUEO PROPIAS

function comprobarFechaIni(&$camposQueFaltan, &$errores) {
        $d = substr(POST("F4_C4"),0,2);
        $m = substr(POST("F4_C4"),3,2);
        $a = substr(POST("F4_C4"),6,4);  
        if (!checkdate($m,$d,$a)) {
	   $errores[] = ETIQ_FECHA_NO_VALIDA;
       	   $camposQueFaltan[] = "F4_C4";
       	} elseif (mktime(0,0,0)>mktime(0,0,0,$m,$d,$a)) {
	        $errores[] = ETIQ_FECHA_INF_ACTUAL;
       	        $camposQueFaltan[] = "F4_C4";
        }
}

function comprobarFechaFin(&$camposQueFaltan, &$errores) {
        $d = substr(POST("F4_C5"),0,2);
        $m = substr(POST("F4_C5"),3,2);
        $a = substr(POST("F4_C5"),6,4);
        if (!checkdate($m,$d,$a)) {
	   $errores[] = ETIQ_FECHA_NO_VALIDA;
       	   $camposQueFaltan[] = "F4_C5";
       	} elseif (mktime(0,0,0)>mktime(0,0,0,$m,$d,$a)) {
	        $errores[] = ETIQ_FECHA_INF_ACTUAL;
       	        $camposQueFaltan[] = "F4_C5";
        }
}


//esta función comprueba si el usuario ha decidido subir una imagen
//para ello, si el campo oculto imgOferta está vacío, prueba a chequear y subir el archivo indicado en $_FILES
//si todo es correcto, en el POST oculto se guardará el nombre
//si no es así, se indicará error como en cualquier otro campo
function comprobarImagenOferta(&$errores, $modo) {
         if (isset($_FILES["archOferta"]["tmp_name"])&&
            ($_FILES["archOferta"]["tmp_name"]!="")) {
            $erroresArchivo = array();
	    tratarArchivo($erroresArchivo,$modo);
	    if ($erroresArchivo) for($i=0;$i<count($erroresArchivo);$i++) $errores[]=$erroresArchivo[$i];
            else {
                 $_POST["imgOferta"] = $_FILES["archOferta"]["name"];
            }
	}
}


function tratarArchivo(&$errores, $modo) {

// 	 $nombreArchivo = strtolower(str_replace(" ", "_",basename($_FILES["archOferta"]['name'])));
//	 if (preg_match("/[^0-9a-zA-Z_.-]/",$nombreArchivo)) {
//	     $errores[] = ETIQ_ARCHIVO_CARACTERES_NO_VALIDOS;
//         } else {  //en principio como yo le cambio el nombre, si el SO es capaz de subir el archivo a temporal, no me preocupo
                     //por el conjunto de caracteres. si hay algún problema, se verá al chequear $_FILES["archOferta"]['error']
                  if (($_FILES["archOferta"]['type']!="image/gif") && ($_FILES["archOferta"]['type']!="image/jpeg")&& ($_FILES["archOferta"]['type']!="image/pjpeg")) {
   	               $errores[] = ETIQ_FORMATO_IMAGEN_NO_VALIDO;
		       $errores[] = ETIQ_AVISO_PROXY;
                  } else { if ($_FILES["archOferta"]['size'] > TAM_IMG_OFE_BYTES) {
         	               $errores[] = ETIQ_TAM_MAX_IMG_OFE;
                         } else {
          	               $d = getimagesize($_FILES["archOferta"]['tmp_name']);
                               if (($d[0]!=ANCHO_IMG_OFE)OR($d[1]!=ALTO_IMG_OFE)) {
			       	  $errores[] = ETIQ_DIM_MAX_IMG_OFE;
			       } else {
				  $errorArchivo = $_FILES["archOferta"]['error'];
				  if ($errorArchivo==UPLOAD_ERR_OK) {
				  	$dir = directorio();
				  	$extension = ($_FILES["archOferta"]['type']=="image/gif") ? ".gif" : ".jpg";
					$archivo = $dir.PREFIJO_ARCH_OFE.$extension;
					$subido = false;
	 				$subido = copy($_FILES["archOferta"]['tmp_name'], $archivo);
	 				if ($subido) {
			    	            if ($modo==ALTA) $_SESSION["archOferta"] = $archivo;
			    	            else $_SESSION["archOferta"] = $archivo;
  			                } else {
	  				    $errores[] = ETIQ_ERR_SUBIR_ARCHIVO;
		    			}
				  } else {
		     		      $errores[] =  ETIQ_ERR_SUBIR_ARCHIVO;;
		 		  }
		 	       }
			 }
                  }
//         }  //del primer if por ahora asteriscado...
}

function salvarImagen($idOferta) {

        if (SESSION("archOferta")) {
            if (file_exists(SESSION("archOferta"))) {
	       $archivoNuevo = RUTA_ABS.DIR_ARCH_OFE.PREFIJO_ARCH_OFE.$idOferta;
    	       if (file_exists($archivoNuevo.".gif")) unlink($archivoNuevo.".gif"); //por si acaso se dejó libre un idOferta... raro...
	       if (file_exists($archivoNuevo.".jpg")) unlink($archivoNuevo.".jpg"); //hay que borrar el que ya existe si no se perdió.
               $archivoNuevo.=substr(basename(SESSION("archOferta")),strpos(basename(SESSION("archOferta")),"."),4);
	       rename(SESSION("archOferta"),$archivoNuevo);
	       return "";
	    } else return ETIQ_ERR_ARCHIVO_PERDIDO;
	}  else return "";

 }
 
 
 function nombreArchivoImagen($modo, $idOferta) {

               if ((SESSION("archOferta"))AND(file_exists(SESSION("archOferta")))) return RAIZ.DIR_TMP_ARCH.obtenerIP()."/".PREFIJO_ARCH_OFE.EXT_GEN_ARCH_OFE; //se indicó archivo en el alta
           elseif ($modo != ALTA) {
                   if (file_exists(RUTA_ABS.DIR_ARCH_OFE.PREFIJO_ARCH_OFE.$idOferta.".gif"))
                       return RAIZ.DIR_ARCH_OFE.PREFIJO_ARCH_OFE.$idOferta.".gif";
               elseif (file_exists(RUTA_ABS.DIR_ARCH_OFE.PREFIJO_ARCH_OFE.$idOferta.".jpg"))
                       return RAIZ.DIR_ARCH_OFE.PREFIJO_ARCH_OFE.$idOferta.".jpg";
                 else  return "";
           } else return "";

}


//////////// FUNCIONES DE TRATAMIENTO


function crearOferta($campos, $idEmpresa, $ofertaBD, $modo, $nivel) {

        $propiedades = array();
        //en el modo MDTU se deshabilitan los campos POST y no se devuelven, salvo el único permitido, activa...
        if ($modo==MDTU) foreach($campos as $campo) $propiedades[$campo["bd"]]=$ofertaBD->obtenerCampo($campo["bd"]);
                   else  foreach($campos as $campo) $propiedades[$campo["bd"]]=POST($campo["post"]);
        if ($modo == ALTA) {
            $propiedades["idOferta"]="";
            $propiedades["idEmpresa"]=$idEmpresa;
        } else {
            $propiedades["activa"] = POST("F4_C6"); //por si se ha modificado el campo "activa"
            $propiedades["idOferta"]   = $ofertaBD->obtenerCampo("idOferta");
            $propiedades["idEmpresa"] = $ofertaBD->obtenerCampo("idEmpresa");
        }
        return new Oferta($propiedades);

}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////

// y esta función es la que muestra el formulario que compone la ficha de un usuario
function mostrarFormOferta($campos, $camposQueFaltan, $errores, $modo=ALTA, $nivel=ANONIMO) {


//primero damos de alta la plantilla y rellenamos títulos y mensajes en la cabecera
        $smarty = nuevaPlantilla();

        if ($modo==ALTA) $tituloPagina = ETIQ_TIT_REG_OFE;
                    else $tituloPagina = ETIQ_TIT_MOD_OFE;

        $smarty->assign('tituloPagina',$tituloPagina);

        if (!$errores) $errores[] = ($modo==ALTA) ? ETIQ_SUBTIT_INTRO_DATOS : "";
        else $smarty->assign('class_mensajes',CLAS_ERROR);

        $smarty->assign('mensajes',$errores);

        $smarty->display(TPL_CABECERA);

        $smarty->assign('opciones_F4_C6', array("1" => "Activada"));
        
        if ($modo == ALTA) $_POST["F4_C6"]=1;

        if (($modo == ALTA) && (!(POST("F4_C4")))) {
            $_POST["F4_C4"]=date("d")."/".date("m")."/".date("Y");
        }

//posteriormente rellenamos todas las variables asociadas a campos POST.
//y su clase a "error" si resulta que el campo está entre los que faltan
        foreach ($campos as $campo) {
          	 $smarty->assign($campo["post"],POST($campo["post"]));
          	 $smarty->assign('class_'.$campo["post"],comprobarCampo($campo["post"],$camposQueFaltan));
        }
        $smarty->assign("imgOferta",POST("imgOferta"));
        $smarty->assign('class_imgOferta',comprobarCampo("imgOferta",$camposQueFaltan));
        $smarty->assign("imagen",nombreArchivoImagen($modo, SESSION_CAMPO("oferta","idOferta")));

////////////////////////////en función del modo y el nivel, establecemos los campos y botones a mostrar

        if (($modo==ALTA)OR($modo==MODI)) $smarty->assign('mostrar_imgOferta',true);

//y ahora los botones y bloqueo de campos...

              if ($modo==ALTA) {  $smarty->assign('mostrar_Enviar',true);
                                  $smarty->assign('enviar',ETIQ_BT_ALTA_OFE);
                                  $smarty->assign('mostrar_Borrar',true);
                                  $smarty->assign('borrar',ETIQ_BT_LIMPIAR);

        } elseif ($modo==MODI) {  $smarty->assign('mostrar_Enviar',true);
                                  $smarty->assign('enviar',ETIQ_BT_ACTU_OFE);
                                  $smarty->assign('mostrar_Borrar',true);
                                  $smarty->assign('borrar',ETIQ_BT_BAJA_OFE);
                                  if ($nivel >= GESTOR) {
                                  $smarty->assign('mostrar_Activar',true);
                                  if (POST("F4_C6")) $smarty->assign('activar',ETIQ_BT_DESACTIVAR_OFE);
                                  else $smarty->assign('activar',ETIQ_BT_ACTIVAR_OFE);
                                  }
                                  $smarty->assign('mostrar_Empresa',true);
                                  $smarty->assign('empresa',ETIQ_BT_VER_EMPRESA);


        } elseif ($modo==MDTU) {  foreach ($campos as $campo) $smarty->assign('bloquear_'.$campo["post"],'disabled="disabled"');
                                  $smarty->assign('mostrar_Activar',true);
                                  if (POST("F4_C6")) $smarty->assign('activar',ETIQ_BT_DESACTIVAR_OFE);
                                  else $smarty->assign('activar',ETIQ_BT_ACTIVAR_OFE);
                                  $smarty->assign('mostrar_Empresa',true);
                                  $smarty->assign('empresa',ETIQ_BT_VER_EMPRESA);
                                  

        } elseif ($modo==BAJA) {  $smarty->assign('mostrar_Borrar',true); //modo especial transitorio cuando en MODI lo que se pide es eliminar el usuario. Pide confirmación
                                  $smarty->assign('borrar',ETIQ_BT_CONFIRMAR_BAJA_OFE);
                                  $smarty->assign('mostrar_Cancelar',true);
                                  $smarty->assign('cancelar',ETIQ_BT_CANCELAR);
                                  foreach ($campos as $campo) $smarty->assign('bloquear_'.$campo["post"],'disabled="disabled"');
        // cualquier otro caso sería visualización, por lo que se bloquea todo y no se muestra un botón. A no ser que haya un mal modo, a este "ELSE" no llegará
        } else  foreach ($campos as $campo) $smarty->assign('bloquear_'.$campo["post"],'disabled="disabled"');

        if ($modo == ALTA) $smarty->assign('action',completarURL(PAG_OFERTAS));
                     else  $smarty->assign('action',completarURL(PAG_OFERTAS."?oferta=".SESSION_CAMPO("oferta","idOferta")));

	 $smarty->display(TPL_FICHA_OFE);

 	 if ($nivel == ANONIMO) {
     	     $smarty->assign('linkPie',completarURL(PAG_USUARIOS));
 	     $smarty->assign('textoLinkPie',ETIQ_LINK_VOLVER);
	 } else {
             if (SESSION("listOfeEmp")) {
       	         $smarty->assign('linkAlt',completarURL(PAG_AREA_PERSONAL));
 	         $smarty->assign('textoLinkAlt',ETIQ_LINK_AREA_PERSONAL);
                 $smarty->assign('linkPie',completarURL(PAG_LIST_OFE_EMP));
  	         $smarty->assign('textoLinkPie',ETIQ_LINK_VOLVER_LIST);
             }elseif (SESSION("listOfertas")) {
       	         $smarty->assign('linkAlt',completarURL(PAG_AREA_PERSONAL));
 	         $smarty->assign('textoLinkAlt',ETIQ_LINK_AREA_PERSONAL);
                 $smarty->assign('linkPie',completarURL(PAG_LIST_OFE));
  	         $smarty->assign('textoLinkPie',ETIQ_LINK_VOLVER_LIST);
             } else {
     	         $smarty->assign('linkPie',completarURL(PAG_AREA_PERSONAL));
 	         $smarty->assign('textoLinkPie',ETIQ_LINK_AREA_PERSONAL);
             }
  	 }
	 $smarty->display(TPL_PIE);
}
?>