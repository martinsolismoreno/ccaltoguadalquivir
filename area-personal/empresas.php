<?php

require_once("../comunes/config.php");

$nivel = chequearConexion();

// arrays que definen los campos POST que se tratan en el formulario y su correspondencia con campos del objeto a tratar
// automatiza su tratamiento, oculta los nombres de las bases de datos, evito duplicidades en campos POST de distintos formulario
// y marco el patrón sobre el que chequear si el campo se ajusta a lo permitido o no
$campos = array( array( "post" => "F3_C1",  "bd"  => "dni"),
                 array( "post" => "F3_C2",  "bd"  => "empresa"),
                 array( "post" => "F3_C3",  "bd"  => "idSector"),
                 array( "post" => "F3_C4",  "bd"  => "descripcion"),
                 array( "post" => "F3_C5",  "bd"  => "direccion"),
                 array( "post" => "F3_C6",  "bd"  => "pedania"),
                 array( "post" => "F3_C7",  "bd"  => "idPoblacion"),
                 array( "post" => "F3_C8",  "bd"  => "telefono1"),
                 array( "post" => "F3_C9",  "bd"  => "telefono2"),
                 array( "post" => "F3_C10", "bd"  => "fax"),
                 array( "post" => "F3_C11", "bd"  => "email"),
                 array( "post" => "F3_C12", "bd"  => "web"),
                 array( "post" => "F3_C13", "bd"  => "horario")
);

//en este se definen los campos POST obligatorios a completar
$camposRequeridos = array ("F3_C1", "F3_C2", "F3_C3", "F3_C4", "F3_C5", "F3_C7", "F3_C8", "F3_C13");

////////////////// LOGICA DE PROCESO /////////////////////////////////////////////////////////////////////////////////////////////////////////



if ($nivel == ANONIMO) {  //si el usuario es anonimo, la opción es permitir el registro
                         //aunque se llame con parámetros GET, lo ignoro, al no estar conectado, se le intenta registrar.

    if (SESSION("tipoUsuario")==T_SOCIO) { //si hemos pasado por el registro previo que nos indica el tipo de usuario
    
       if (SESSION("usuarioRG")) { //y también por el registro de los datos del usuario

	          if (POST("enviar")) {  //si se ha pulsado el boton de dar de alta
                      $errores = array();
                      $camposQueFaltan = array();
	              comprobarCampos($campos,$camposRequeridos,$camposQueFaltan,$errores,ALTA);
                      comprobarImagenEmpresa($errores,ALTA);
                      $_SESSION["imgEmpresaRG"]=$_POST["imgEmpresa"];
                      if ($errores) mostrarFormEmpresa($campos,$camposQueFaltan,$errores,ALTA);
	              else altaEmpresa($campos);

	    } elseif (POST("borrar")) {  //si se ha preferido limpiar, se borra todo
		      inicializarCamposPOST($campos);
		      $_POST["imgEmpresa"]="";
		      unset($_SESSION["empresaRG"]);
		      unset($_SESSION["imgEmpresaRG"]);
		      unset($_SESSION["archEmpresaRG"]);
                      mostrarFormEmpresa($campos,array(),array(),ALTA);

	    } else {
                    if (SESSION("empresaRG")) {
                        cargarCamposPOST($campos,SESSION("empresaRG"));
                        $_POST["imgEmpresa"]=SESSION("imgEmpresaRG");
                    }
                    mostrarFormEmpresa($campos,array(),array(),ALTA);  //si se ha recargado simplemente la página, se muestran de nuevo los datos
            }

       } else redirigir(PAG_USUARIOS); //si no se ha pasado previamente por el registro de los datos del usuario

    } else redirigir(PAG_REG_PPAL); //si no se ha pasado previamente por la primera página del registro, lo redigirmos allí directamente

} else { //en esta parte se lleva a cabo el tratamiento para usuarios conectados, que trata la modificación o sólo visualización de los datos

         $error="";
         $idUsuarioPT = "";
         $idUsuarioMD = "";
         $empresaPT = "";
         
         //1. comprobamos si hay peticion explícita y que el valor de idUsuario es correcto (la empresa se pide por el id de su usuario)
         if (GET("usuario")) {
             if (!$error=chequearCampo("idUsuario", GET("usuario"))) $idUsuarioPT = GET("usuario");
         } else {
             if (SESSION_CAMPO("usuario","tipoUsuario")==T_CLIENTE) $error=ETIQ_CLIENTE_NO_EMPRESA;
             else $error=ETIQ_EMPRESA_REGISTRADA; //si no se pide usuario, es como si fuese un registro, y no es válido para un usuario que está conectado
         }

         //2. si se ha hecho correctamente la petición, comprobamos el nivel de acceso del usuario conectado
         if (!$error) {
                 if (($idUsuarioPT)AND($idUsuarioPT==SESSION_CAMPO("usuario","idUsuario"))) $modo= MODI;  //si el usuario pedido es el conectado, puede hacer cambios en sus propios datos
             elseif ($nivel==ADMON) $modo = MODI; //pero si es administrador, puede hacerlo todo también con otros usuarios
             elseif ($nivel==GESTOR) $modo = VISU;  //si el usuario a tratar no es uno mismo (no se cumple anterior if), se debe tener rango de gestor mínimo para visualizar los datos
               else  $error=ETIQ_USUARIO_NO_AUTORIZADO; //en otro caso no hay privilegios suficientes, se marca error.
         }

        //3. si no hay error, comprobamos que el usuario pedido en verdad exite en la BD
        //aunque parezca una acceso gratuito, si ha ocurrido algo en la base de datos o se ha borrado, esto impide que se siga intentando tratar.
         if (!$error)
             if (!$empresaPT = Empresa::obtenerPorIdUsuario($idUsuarioPT)) $error=ETIQ_EMPRESA_NO_EXISTE;

         //4. si no hay errores, la peticion es correcta, hay privilegios y el usuario existe en la base de datos.
         if (!$error) {

             $idUsuarioMD = SESSION_CAMPO("empresaMD","idUsuario"); //si hay sesión activa, recuperamos el campo "idUsuario" que se estaba tratando.

             if ($idUsuarioPT == $idUsuarioMD) {//la petición es la misma, continuamos la sesion anterior
             
      	                   if (POST("vista")&&($modo==VISU)) {  //queremos ir a ver los datos del usuario asociado a la empresa
                               redirigir(PAG_VPREV_EMP."?usuario=".$empresaPT->obtenerCampo("idUsuario"));

      	             } elseif (POST("enviar") OR POST("vista")) {  //si se ha pulsado el boton de enviar, se quiere actualizar los datos
                               $errores = array();
                               $camposQueFaltan = array();
                               comprobarCampos($campos,$camposRequeridos,$camposQueFaltan,$errores,$modo);
                               comprobarImagenEmpresa($errores,$modo);
                               $_SESSION["imgEmpresaMD"]=$_POST["imgEmpresa"];
                               if ($errores) mostrarFormEmpresa($campos,$camposQueFaltan,$errores,$modo,$nivel);
                               else { $empresa = crearEmpresa($campos,$modo,$nivel);
                                      if (sonIguales($empresa,$empresaPT,$campos)&&(!SESSION("archEmpresaMD"))) {
                                         if (!POST("vista")) $errores[]=ETIQ_REG_IGUALES;
                                      }
                                      $_SESSION["empresaMD"]=$empresa;
                                      if ($errores) mostrarFormEmpresa($campos,$camposQueFaltan,$errores,$modo,$nivel);
                                      else redirigir(PAG_VPREV_EMP."?usuario=".$empresa->obtenerCampo("idUsuario"));
                               }

      	             } elseif (POST("usuario")) {  //queremos ir a ver los datos del usuario asociado a la empresa
                               redirigir(PAG_USUARIOS."?usuario=".SESSION_CAMPO("empresaMD","idUsuario"));

      	             } elseif (POST("ofertas")) {  //queremos ir a ver las ofertas asociadas a la empresa
	 	               redirigir(PAG_LIST_OFE_EMP."?empresa=".SESSION_CAMPO("empresaMD","idEmpresa"));

                     } else {  //no se ha pulsado botón (se habrá recargado la página con F5 o venimos de otra página) o se pulsó "cancelar"
                               cargarCamposPOST($campos,SESSION("empresaMD"));
                               $_POST["imgEmpresa"]=SESSION("imgEmpresaMD");
                               mostrarFormEmpresa($campos,array(),array(),$modo,$nivel);
                     }

              } else { //si el usuario pedido no era el de la petición activa (o porque no existía aún), se carga de la base de datos
                       cargarCamposPOST($campos,$empresaPT);
                       $_POST["imgEmpresa"]="";
                       $_SESSION["empresaMD"]=$empresaPT;
		       unset($_SESSION["imgEmpresaMD"]);
		       unset($_SESSION["archEmpresaMD"]);
                       mostrarFormEmpresa($campos,array(),array(),$modo,$nivel);

              }

         } else { //y aquí se muestran los errores que se han detectado


                 if ($error==ETIQ_EMPRESA_REGISTRADA) informacion($error,INFORMACION,"",PAG_AREA_PERSONAL,ETIQ_LINK_AREA_PERSONAL);
             elseif ($error==ETIQ_CLIENTE_NO_EMPRESA) informacion($error,INFORMACION,"",PAG_AREA_PERSONAL,ETIQ_LINK_AREA_PERSONAL);
             elseif ($error==ETIQ_USUARIO_NO_AUTORIZADO) informacion($error,AREA_RESTRINGIDA,"",PAG_AREA_PERSONAL,ETIQ_LINK_AREA_PERSONAL);
               else  informacion($error,ERROR);

         }

}

/////////////////// FUNCIONES /////////////////////////////////////////////////////////////////////////////////////////////


//// FUNCIONES DE CHEQUEO PROPIAS


function comprobarDNI(&$camposQueFaltan, &$errores) {
    	$cad = LETRAS_DNI;
    	if (strtoupper($_POST["F3_C1"][8]) != $cad[substr(POST("F3_C1"),0,8)%23]) {
           $errores[]=ETIQ_DNI_NO_VALIDO;
     	   $camposQueFaltan[] = "F3_C1";
	} elseif (Empresa::obtenerPorDNI(POST("F3_C1"))) {
	          $errores[] = ETIQ_DNI_YA_EXISTE;
                  $camposQueFaltan[] = "F3_C1";
	}
}


//esta función comprueba si el usuario ha decidido subir una imagen
//para ello, si el campo oculto imgEmpresa está vacío, prueba a chequear y subir el archivo indicado en $_FILES
//si todo es correcto, en el POST oculto se guardará el nombre
//si no es así, se indicará error como en cualquier otro campo
function comprobarImagenEmpresa(&$errores, $modo) {
         if (isset($_FILES["archEmpresa"]["tmp_name"])&&
            ($_FILES["archEmpresa"]["tmp_name"]!="")) {
            $erroresArchivo = array();
	    tratarArchivo($erroresArchivo,$modo);
	    if ($erroresArchivo) for($i=0;$i<count($erroresArchivo);$i++) $errores[]=$erroresArchivo[$i];
            else {
                 $_POST["imgEmpresa"] = $_FILES["archEmpresa"]["name"];
            }
	}
}


function tratarArchivo(&$errores, $modo) {

// 	 $nombreArchivo = strtolower(str_replace(" ", "_",basename($_FILES["archEmpresa"]['name'])));
//	 if (preg_match("/[^0-9a-zA-Z_.-]/",$nombreArchivo)) {
//	     $errores[] = ETIQ_ARCHIVO_CARACTERES_NO_VALIDOS;
//         } else {  //en principio como yo le cambio el nombre, si el SO es capaz de subir el archivo a temporal, no me preocupo
                     //por el conjunto de caracteres. si hay algún problema, se verá al chequear $_FILES["archEmpresa"]['error']
                  if (($_FILES["archEmpresa"]['type']!="image/gif") && ($_FILES["archEmpresa"]['type']!="image/jpeg")&& ($_FILES["archOferta"]['type']!="image/pjpeg")) {
   	               $errores[] = ETIQ_FORMATO_IMAGEN_NO_VALIDO;
		       $errores[] = ETIQ_AVISO_PROXY;
                  } else { if ($_FILES["archEmpresa"]['size'] > TAM_IMG_EMP_BYTES) {
         	               $errores[] = ETIQ_TAM_MAX_IMG_EMP;
                         } else {
          	               $d = getimagesize($_FILES["archEmpresa"]['tmp_name']);
                               if (($d[0]!=ANCHO_IMG_EMP)OR($d[1]!=ALTO_IMG_EMP)) {
			       	  $errores[] = ETIQ_DIM_MAX_IMG_EMP;
			       } else {
				  $errorArchivo = $_FILES["archEmpresa"]['error'];
				  if ($errorArchivo==UPLOAD_ERR_OK) {
				  	$dir = directorio();
				  	$extension = ($_FILES["archEmpresa"]['type']=="image/gif") ? ".gif" : ".jpg";
					$archivo = $dir.PREFIJO_ARCH_EMP.$extension;
					$subido = false;
	 				$subido = copy($_FILES["archEmpresa"]['tmp_name'], $archivo);
	 				if ($subido) {
			    	            if ($modo==ALTA) $_SESSION["archEmpresaRG"] = $archivo;
			    	            else $_SESSION["archEmpresaMD"] = $archivo;
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


function nombreArchivoImagen($modo, $idEmpresa) {

               if ((SESSION("archEmpresaRG"))AND(file_exists(SESSION("archEmpresaRG")))) return RAIZ.DIR_TMP_ARCH.obtenerIP()."/".PREFIJO_ARCH_EMP.EXT_GEN_ARCH_EMP;
               if ((SESSION("archEmpresaMD"))AND(file_exists(SESSION("archEmpresaMD")))) return RAIZ.DIR_TMP_ARCH.obtenerIP()."/".PREFIJO_ARCH_EMP.EXT_GEN_ARCH_EMP;
           elseif ($modo != ALTA) {
                   if (file_exists(RUTA_ABS.DIR_ARCH_EMP.PREFIJO_ARCH_EMP.$idEmpresa.".gif"))
                       return RAIZ.DIR_ARCH_EMP.PREFIJO_ARCH_EMP.$idEmpresa.".gif";
               elseif (file_exists(RUTA_ABS.DIR_ARCH_EMP.PREFIJO_ARCH_EMP.$idEmpresa.".jpg"))
                       return RAIZ.DIR_ARCH_EMP.PREFIJO_ARCH_EMP.$idEmpresa.".jpg";
                 else  return "";
           } else return "";

}

//////////// FUNCIONES DE TRATAMIENTO


function crearEmpresa($campos, $modo, $nivel) {


        $propiedades = array();
        foreach($campos as $campo) $propiedades[$campo["bd"]]=POST($campo["post"]);
        if ($modo == ALTA) {
            $propiedades["idEmpresa"]="";
            $propiedades["idUsuario"]=""; //no se asignará hasta la pantalla siguiente cuando se confirme el alta
        } else {
            if ($nivel >= PREMIUM) $propiedades["validada"]=1; //si actualiza una empresa un usuario PREMIUM (que será la suya propia) o un GESTOR O ADMON, se da por válida.
                                                              //en otro caso, por defecto, el objeto empresa se crea como NO validado (a cero), con lo cual, habría que volver a validarla.
            $propiedades["idEmpresa"]=SESSION_CAMPO("empresaMD","idEmpresa");
            $propiedades["idUsuario"]=SESSION_CAMPO("empresaMD","idUsuario");
        }
        return new Empresa($propiedades);

}


function altaEmpresa($campos) {

	$_SESSION["empresaRG"]=crearEmpresa($campos,ALTA,ANONIMO);
        redirigir(PAG_VPREV_EMP);

}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////

// y esta función es la que muestra el formulario que compone la ficha de un usuario
function mostrarFormEmpresa($campos, $camposQueFaltan, $errores, $modo=ALTA, $nivel=ANONIMO) {


//primero damos de alta la plantilla y rellenamos títulos y mensajes en la cabecera
        $smarty = nuevaPlantilla();

        if ($modo==ALTA) $tituloPagina = ETIQ_TIT_REG_EMP;
                    else $tituloPagina = ETIQ_TIT_MOD_EMP;

        $smarty->assign('tituloPagina',$tituloPagina);

        if (!$errores) $errores[] = ($modo==ALTA) ? ETIQ_SUBTIT_INTRO_DATOS : "";
        else $smarty->assign('class_mensajes',CLAS_ERROR);

        $smarty->assign('mensajes',$errores);

        $smarty->display(TPL_CABECERA);

//rellenamos las opciones de los campos con opciones a elegir

        $smarty->assign('opciones_F3_C3', Sector::obtenerListadoDeSectores());

        $smarty->assign('opciones_F3_C7', Poblacion::obtenerListadoDePoblaciones());

//posteriormente rellenamos todas las variables asociadas a campos POST.
//y su clase a "error" si resulta que el campo está entre los que faltan
        foreach ($campos as $campo) {
          	 $smarty->assign($campo["post"],POST($campo["post"]));
          	 $smarty->assign('class_'.$campo["post"],comprobarCampo($campo["post"],$camposQueFaltan));
        }
        $smarty->assign("imgEmpresa",POST("imgEmpresa"));
        $smarty->assign('class_imgEmpresa',comprobarCampo("imgEmpresa",$camposQueFaltan));
        $smarty->assign("imagen",nombreArchivoImagen($modo, SESSION_CAMPO("empresaMD","idEmpresa")));


////////////////////////////en función del modo y el nivel, establecemos los campos y botones a mostrar

//y si no es un alta, el dni siempre se bloquea. NO SE PERMITE MODIFICAR. Es un dato intrínseco que conllevan dar de baja el usuario y
//darse de nuevo un alta si quiere modificarlo (como si fuese un usuario nuevo, pues identifican completamente el usuario que tiene empresa).
        if ($modo!=ALTA) $smarty->assign('bloquear_F3_C1','readonly="readonly"'); //en modo readonly son devueltos al submitir el formulario

        if ($modo!=VISU) $smarty->assign('mostrar_imgEmpresa',true); //excepto para visualización, permitimos cambiar el imagen de la empresa

//y ahora los botones y bloqueo de campos...

              if ($modo==ALTA) {  $smarty->assign('mostrar_Enviar',true);
                                  $smarty->assign('enviar',ETIQ_BT_VISU_ALTA_EMP);
                                  $smarty->assign('mostrar_Borrar',true);
                                  $smarty->assign('borrar',ETIQ_BT_LIMPIAR);

        } elseif ($modo==MODI) {  $smarty->assign('mostrar_Enviar',true);
                                  $smarty->assign('enviar',ETIQ_BT_VISU_ACTU_EMP);
                                  $smarty->assign('mostrar_Vista',true);
                                  $smarty->assign('vista',ETIQ_BT_VISTA_EMP);
                                  $smarty->assign('mostrar_Usuario',true);
                                  $smarty->assign('usuario',ETIQ_BT_VER_USU);
                                  $tipoUsuario = Usuario::obtenerPorIdUsuario(SESSION_CAMPO("empresaMD","idUsuario"))->obtenerCampo("tipoUsuario");
                                  if ($tipoUsuario!=T_SOCIO) {
                                     $smarty->assign('mostrar_Ofertas',true);
                                     $smarty->assign('ofertas',ETIQ_BT_LIST_OFERTAS);
                                  }

        } elseif ($modo==VISU) {  $smarty->assign('mostrar_Usuario',true);
                                  $smarty->assign('usuario',ETIQ_BT_VER_USU);
                                  $smarty->assign('mostrar_Vista',true);
                                  $smarty->assign('vista',ETIQ_BT_VISTA_EMP);
                                  $tipoUsuario = Usuario::obtenerPorIdUsuario(SESSION_CAMPO("empresaMD","idUsuario"))->obtenerCampo("tipoUsuario");
                                  if ($tipoUsuario!=T_SOCIO) {
                                     $smarty->assign('mostrar_Ofertas',true);
                                     $smarty->assign('ofertas',ETIQ_BT_LIST_OFERTAS);
                                  }
                                  foreach ($campos as $campo) $smarty->assign('bloquear_'.$campo["post"],'disabled="disabled"');
        // cualquier otro caso sería visualización, por lo que se bloquea todo y no se muestra un botón. A no ser que haya un mal modo, a este "ELSE" no llegará
        } else  foreach ($campos as $campo) $smarty->assign('bloquear_'.$campo["post"],'disabled="disabled"');

        if ($modo == ALTA) $smarty->assign('action',completarURL(PAG_EMPRESAS));
                     else  $smarty->assign('action',completarURL(PAG_EMPRESAS."?usuario=".SESSION_CAMPO("empresaMD","idUsuario")));

	 $smarty->display(TPL_FICHA_EMP);

 	 if ($nivel == ANONIMO) {
     	     $smarty->assign('linkPie',completarURL(PAG_USUARIOS));
 	     $smarty->assign('textoLinkPie',ETIQ_LINK_VOLVER);
	 } else {
             if (SESSION("listEmpresas")) {
       	         $smarty->assign('linkAlt',completarURL(PAG_AREA_PERSONAL));
 	         $smarty->assign('textoLinkAlt',ETIQ_LINK_AREA_PERSONAL);
                 $smarty->assign('linkPie',completarURL(PAG_LIST_EMP));
  	         $smarty->assign('textoLinkPie',ETIQ_LINK_VOLVER_LIST);
             } elseif (SESSION("listOfertas")) {
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