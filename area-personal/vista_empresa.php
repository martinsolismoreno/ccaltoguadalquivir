<?php

require_once("../comunes/config.php");

// arrays de correspondencia de las variables de la plantilla con el valor del objeto que van a representar
$campos = array( array( "var" => "empresa",     "bd"  => "empresa"),
                 array( "var" => "descripcion", "bd"  => "descripcion"),
                 array( "var" => "direccion",   "bd"  => "direccion"),
                 array( "var" => "pedania",     "bd"  => "pedania"),
                 array( "var" => "telefono1",   "bd"  => "telefono1"),
                 array( "var" => "telefono2",   "bd"  => "telefono2"),
                 array( "var" => "fax",         "bd"  => "fax"),
                 array( "var" => "email",       "bd"  => "email"),
                 array( "var" => "web",         "bd"  => "web"),
                 array( "var" => "horario",     "bd"  => "horario")
);


////////////////// LOGICA DE PROCESO /////////////////////////////////////////////////////////////////////////////////////////////////////////

$nivel = chequearConexion();


if ($nivel == ANONIMO) {  //si el usuario es anonimo, la opción es permitir el registro
                         //aunque se llame con parámetros GET, lo ignoro, al no estar conectado, se le intenta registrar.

    if (SESSION("tipoUsuario")==T_SOCIO) { //si hemos pasado por el registro previo que nos indica el tipo de usuario

       if (SESSION("usuarioRG")) { //y también por el registro de los datos del usuario

           if (SESSION("empresaRG")) { //y que también se haya pasado por el registro de empresa

  	          if (POST("enviar")) altaEmpresa($campos);

  	          else mostrarVistaEmpresa($campos,SESSION("empresaRG"),ALTA,$nivel);  //mostramos los datos que están guardados en la variable de sesión

            } else redirigir(PAG_EMPRESAS); //si no se ha pasado previamente por el registro de la empresa

       } else redirigir(PAG_USUARIOS); //si no se ha pasado previamente por el registro de los datos del usuario

    } else redirigir(PAG_REG_PPAL); //si no se ha pasado previamente por la primera página del registro, lo redigirmos allí directamente

} else { //en esta parte se lleva a cabo el tratamiento para usuarios conectados, visualiza los datos de una empresa concreta

         $error="";
         $idUsuarioPT = "";
         $idUsuarioMD = "";
         $empresaPT = "";
         
         //1. comprobamos si hay peticion explícita y que el valor de idUsuario es correcto (la empresa se pide por el id de su usuario)
         if (GET("usuario")) {
             if (!$error=chequearCampo("idUsuario", GET("usuario"))) $idUsuarioPT = GET("usuario");
         } else {
             if (SESSION_CAMPO("usuario","tipoUsuario")==T_CLIENTE) $error=ETIQ_CLIENTE_NO_VISTA_EMP;
             else $error=ETIQ_NO_ID_EMPRESA; //si no se pide usuario, es como si fuese un registro, y no es válido para un usuario que está conectado
         }

         //2. si se ha hecho correctamente la petición, comprobamos el nivel de acceso del usuario conectado
         if (!$error) {
                 if (($idUsuarioPT)AND($idUsuarioPT==SESSION_CAMPO("usuario","idUsuario"))) $modo= MODI;  //si el usuario pedido es el conectado, podrá aceptar una modificacion
             elseif ($nivel==ADMON) $modo = MODI; //o eres administrador también
             elseif ($nivel==GESTOR) $modo = VISU;  //pero los gestores solo podrán ver la vista previa
               else  $error=ETIQ_USUARIO_NO_AUTORIZADO; //en otro caso no hay privilegios suficientes, se marca error.
         }

        //3. si no hay error, comprobamos que el usuario pedido en verdad exite en la BD
        //aunque parezca una acceso gratuito, si ha ocurrido algo en la base de datos o se ha borrado, esto impide que se siga intentando tratar.
         if (!$error)
             if (!$empresaPT = Empresa::obtenerPorIdUsuario($idUsuarioPT)) $error=ETIQ_EMPRESA_NO_EXISTE;

         //4. si no hay errores, la peticion es correcta, hay privilegios y el usuario existe en la base de datos.
         if (!$error) {

             $idUsuarioMD = SESSION_CAMPO("empresaMD","idUsuario"); //si hay sesión activa, es que venimos de modificar, y tomamos esos datos para mostrar

             if (($idUsuarioPT == $idUsuarioMD) && //la petición de la empresa es la misma de una que hubiese pendiente
                 ((!sonIguales($empresaPT,SESSION("empresaMD"),$campos))OR //pero que haya alguna modificación (puede venir como modo vista, aunque exista MD (se quedó pendiente sin modificar)
                 (SESSION("archEmpresaMD"))) && //o que entre las modificaones sea un nuevo fichero de logotipo
                 ($modo==MODI)) {  //y por supuesto que tenga permiso de modificación

      	                   if (POST("enviar")) actualizaEmpresa();
                         else { if (POST("validar")) {
                                    SESSION("empresaMD")->validarEmpresa(); //es funcion hace firme la peticion en la bd, por si al final no se actualiza
                                    SESSION("empresaMD")->darPorValida(); //esta actualiza la variable de sesion para que no revierta lo anterior, ya que si ha llegado aquí, es porque no estaba validada
                                }
                                mostrarVistaEmpresa($campos,SESSION("empresaMD"),MODI,$nivel);
                         }

              } else { //si el usuario pedido no era el de la petición activa, no veníamos de una modificación, así que sólo visualizaremos sus datos

                       if (POST("validar")) {
                           $empresaPT->validarEmpresa();
                           if (SESSION("listEmpPte")) redirigir(PAG_LIST_PTE);
                       }
                       mostrarVistaEmpresa($campos,$empresaPT,VISU,$nivel);

              }

         } else { //y aquí se muestran los errores que se han detectado

                 if ($error==ETIQ_EMPRESA_REGISTRADA) informacion($error,INFORMACION,"",PAG_AREA_PERSONAL,ETIQ_LINK_AREA_PERSONAL);
             elseif ($error==ETIQ_CLIENTE_NO_EMPRESA) informacion($error,INFORMACION,"",PAG_AREA_PERSONAL,ETIQ_LINK_AREA_PERSONAL);                 
             elseif ($error==ETIQ_USUARIO_NO_AUTORIZADO) informacion($error,AREA_RESTRINGIDA,"",PAG_AREA_PERSONAL,ETIQ_LINK_AREA_PERSONAL);
               else  informacion($error,ERROR);

         }

}

/////////////////// FUNCIONES /////////////////////////////////////////////////////////////////////////////////////////////


//////////// FUNCIONES DE TRATAMIENTO


function crearEmpresa($campos, $idUsuario) {

        $propiedades = array();
        foreach($campos as $campo) $propiedades[$campo["bd"]]=SESSION_CAMPO("empresaRG",$campo["bd"]);
        $propiedades["idUsuario"]=$idUsuario;
        return new Empresa($propiedades);

}


function altaEmpresa($campos) {

        $errorArchivo = false;
        SESSION("usuarioRG")->grabarEnBD();
        $usuarioGrabado = Usuario::obtenerPorUsuario(SESSION_CAMPO("usuarioRG","usuario"));
 	$idUsuario = $usuarioGrabado->obtenerCampo("idUsuario");
 	SESSION("empresaRG")->actualizarIdUsuario($idUsuario);
  	SESSION("empresaRG")->grabarEnBD();
	$empresaGrabada = Empresa::obtenerPorIdUsuario($idUsuario);
        if (SESSION("archEmpresaRG")) {
            if (file_exists(SESSION("archEmpresaRG"))) { //debe existir, porque si no se ha dado uno de alta, pero por si acaso..
	    $archivoNuevo = RUTA_ABS.DIR_ARCH_EMP.PREFIJO_ARCH_EMP.$empresaGrabada->obtenerCampo("idEmpresa");
	    if (file_exists($archivoNuevo.".gif")) unlink($archivoNuevo.".gif"); //por si acaso se dejó libre un idEmpresa... raro...
	    if (file_exists($archivoNuevo.".jpg")) unlink($archivoNuevo.".jpg"); //hay que borrar el que ya existe si no se perdió.
            $archivoNuevo.=substr(basename(SESSION("archEmpresaRG")),strpos(basename(SESSION("archEmpresaRG")),PREFIJO_ARCH_EMP)+strlen(PREFIJO_ARCH_EMP),4);
	    rename(SESSION("archEmpresaRG"),$archivoNuevo);
	    } else $errorArchivo=true;
	}
        unset($_SESSION["archEmpresaRG"]);
        unset($_SESSION["imgEmpresaRG"]);
        unset($_SESSION["empresaRG"]);
  	unset($_SESSION["usuarioRG"]);
  	unset($_SESSION["tipoUsuario"]);
        if ($errorArchivo) informacion(ETIQ_REGISTRO_OK_EMP_ERR_ARCH,ENHORABUENA);
        else informacion(ETIQ_REGISTRO_OK_EMP,ENHORABUENA);

}

function actualizaEmpresa() {

        $errorArchivo = false;  
        SESSION("empresaMD")->actualizarEnBD();
        if (SESSION("archEmpresaMD")) {
            if (file_exists(SESSION("archEmpresaMD"))) {
	    $archivoNuevo = RUTA_ABS.DIR_ARCH_EMP.PREFIJO_ARCH_EMP.(SESSION_CAMPO("empresaMD","idEmpresa"));
	    if (file_exists($archivoNuevo.".gif")) unlink($archivoNuevo.".gif"); //hay que borrar el que ya existe si no se perdió.
	    if (file_exists($archivoNuevo.".jpg")) unlink($archivoNuevo.".jpg"); //hay que borrar el que ya existe si no se perdió.
            $archivoNuevo.=substr(basename(SESSION("archEmpresaMD")),strpos(basename(SESSION("archEmpresaMD")),PREFIJO_ARCH_EMP)+strlen(PREFIJO_ARCH_EMP),4);
	    rename(SESSION("archEmpresaMD"),$archivoNuevo);
	    } else $errorArchivo=true;
	}
	$idUsuario = SESSION_CAMPO("empresaMD","idUsuario");
	$validada  = SESSION_CAMPO("empresaMD","validada");
        unset($_SESSION["archEmpresaMD"]);
        unset($_SESSION["imgEmpresaMD"]);
        unset($_SESSION["empresaMD"]);
        if ($validada) 
            if ($errorArchivo) informacion(ETIQ_EMP_ACTU_ERR_ARCH,INFORMACION,"",PAG_EMPRESAS."?usuario=".$idUsuario,ETIQ_LINK_VOLVER);
            else informacion(ETIQ_EMP_ACTU,INFORMACION,"",PAG_EMPRESAS."?usuario=".$idUsuario,ETIQ_LINK_VOLVER);
        else
            if ($errorArchivo) informacion(ETIQ_EMP_PDTE_ERR_ARCH,INFORMACION,"",PAG_EMPRESAS."?usuario=".$idUsuario,ETIQ_LINK_VOLVER);
            else informacion(ETIQ_EMP_PDTE,INFORMACION,"",PAG_EMPRESAS."?usuario=".$idUsuario,ETIQ_LINK_VOLVER);

}


function nombreArchivoImagen($modo, $empresa) {

             if  (($modo == ALTA) AND (SESSION("archEmpresaRG"))AND(file_exists(SESSION("archEmpresaRG")))) return RAIZ.DIR_TMP_ARCH.obtenerIP()."/".PREFIJO_ARCH_EMP.EXT_GEN_ARCH_EMP; //se indicó archivo en el alta
         elseif  (($modo == MODI) AND (SESSION("archEmpresaMD"))AND(file_exists(SESSION("archEmpresaMD")))) return RAIZ.DIR_TMP_ARCH.obtenerIP()."/".PREFIJO_ARCH_EMP.EXT_GEN_ARCH_EMP; //se indicó archivo en la modificacion
         elseif  (($modo == ALTA) AND (!SESSION("archEmpresaRG"))) { //si es un alta y no se subió archivo, le aplicamos una imagen comun
	  	 $dir = directorio();
		 $archivo = $dir.PREFIJO_ARCH_EMP.EXT_GEN_ARCH_EMP;
		 $subido = false;
		 $subido = copy(RUTA_ABS.DIR_ARCH_EMP.PREFIJO_ARCH_EMP.EXT_GEN_ARCH_EMP,$archivo);
	 	 if ($subido) {
                     $_SESSION["archEmpresaRG"] = $archivo; //le hemos asignado al alta un archivo generico y con el "copy" simulado que lo subió el usuario en el registro
                     return RAIZ.DIR_TMP_ARCH.obtenerIP()."/".PREFIJO_ARCH_EMP.EXT_GEN_ARCH_EMP;
                 } else return ""; //indica que no se ha podido ni asignar una imagen genérica...

       }else     if (file_exists(RUTA_ABS.DIR_ARCH_EMP.PREFIJO_ARCH_EMP.($empresa->obtenerCampo("idEmpresa")).".gif"))
                     return RAIZ.DIR_ARCH_EMP.PREFIJO_ARCH_EMP.($empresa->obtenerCampo("idEmpresa")).".gif";
             elseif (file_exists(RUTA_ABS.DIR_ARCH_EMP.PREFIJO_ARCH_EMP.($empresa->obtenerCampo("idEmpresa")).".jpg"))
                     return RAIZ.DIR_ARCH_EMP.PREFIJO_ARCH_EMP.($empresa->obtenerCampo("idEmpresa")).".jpg";
               else  return RAIZ.DIR_ARCH_EMP.PREFIJO_ARCH_EMP.EXT_GEN_ARCH_EMP;


}



//////////////////////////////////////////////////////////////////////////////////////////////////////////////

// y esta función es la que muestra el formulario que compone la ficha de un usuario
function mostrarVistaEmpresa($campos, $empresa, $modo, $nivel) {

//primero damos de alta la plantilla y rellenamos títulos y mensajes en la cabecera
        $smarty = nuevaPlantilla();

        $smarty->assign('tituloPagina',ETIQ_TIT_VISTA_PREV_EMP);

        $mensajes = array();
        $mensajes[] = ($modo!=VISU) ? ETIQ_SUBTIT_REPASAR_DATOS : "";

        $smarty->assign('mensajes',$mensajes);

        $smarty->display(TPL_CABECERA);

//posteriormente rellenamos todas las variables asociadas a datos a visualizar
        foreach ($campos as $campo) $smarty->assign($campo["var"],$empresa->obtenerCampo($campo["bd"]));
        $poblacion = Poblacion::obtenerPorIdPoblacion($empresa->obtenerCampo("idPoblacion"));
        $smarty->assign("poblacion",$poblacion->obtenerCampo("poblacion"));
        $smarty->assign("imagen",nombreArchivoImagen($modo, $empresa));
        $smarty->assign("ofertas",completarURL(PAG_PPAL_OFERTAS."?empresa=".$empresa->obtenerCampo("idEmpresa")));

//y ahora los botones y bloqueo de campos...

              if ($modo==ALTA) {  $smarty->assign('mostrar_Enviar',true);
                                  $smarty->assign('enviar',ETIQ_BT_CONF_ALTA_EMP);

        } elseif ($modo==MODI) {  $smarty->assign('mostrar_Enviar',true);
                                  $smarty->assign('enviar',ETIQ_BT_CONF_ACTU_EMP);

        }

        if ((($modo == MODI) OR ($modo == VISU)) AND ($nivel >= GESTOR)) {
            if (!Empresa::empresaValidada($empresa->obtenerCampo("idEmpresa"))) {
                 $smarty->assign('mostrar_Validar',true);
                 $smarty->assign('validar',ETIQ_BT_VALIDAR_EMP);
       	         $smarty->assign('linkAlt',"mailto:".Usuario::obtenerPorIdUsuario($empresa->obtenerCampo("idUsuario"))->obtenerCampo("email"));
 	         $smarty->assign('textoLinkAlt',ETIQ_LINK_CORREO_USU);
            }
        }


        if ($modo == ALTA) $smarty->assign('action',completarURL(PAG_VPREV_EMP));
                     else  $smarty->assign('action',completarURL(PAG_VPREV_EMP."?usuario=".$empresa->obtenerCampo("idUsuario")));

       $smarty->display(TPL_VISTA_EMP);


 	 if ($modo == ALTA) {
     	     $smarty->assign('linkPie',completarURL(PAG_EMPRESAS));
 	     $smarty->assign('textoLinkPie',ETIQ_LINK_VOLVER);
	 } else {
             if (SESSION("listEmpPte")) {
                 $smarty->assign('linkPie',completarURL(PAG_LIST_PTE));
  	         $smarty->assign('textoLinkPie',ETIQ_LINK_VOLVER_LIST);
             } else {
                 $smarty->assign('linkPie',completarURL(PAG_EMPRESAS."?usuario=".$empresa->obtenerCampo("idUsuario")));
  	         $smarty->assign('textoLinkPie',ETIQ_LINK_VOLVER);
             }
  	 }

	 $smarty->display(TPL_PIE);
}
?>