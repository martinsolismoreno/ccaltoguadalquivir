<?php

require_once("../comunes/config.php");

// arrays que definen los campos POST que se tratan en el formulario y su correspondencia con campos del objeto a tratar
// automatiza su tratamiento, oculta los nombres de las bases de datos, evito duplicidades en campos POST de distintos formulario
// y marco el patrón sobre el que chequear si el campo se ajusta a lo permitido o no
$campos = array( array( "post" => "F2_C1",  "bd" => "usuario"),
                 array( "post" => "F2_C2",  "bd" => "tipoUsuario"),
                 array( "post" => "F2_C3",  "bd" => "password"),
                 array( "post" => "F2_C4",  "bd" => "password"),
                 array( "post" => "F2_C5",  "bd" => "email"),
                 array( "post" => "F2_C6",  "bd" => "nombre"),
                 array( "post" => "F2_C7",  "bd" => "apellido1"),
                 array( "post" => "F2_C8",  "bd" => "apellido2"),
                 array( "post" => "F2_C9",  "bd" => "edad"),
                 array( "post" => "F2_C10", "bd" => "sexo"),
                 array( "post" => "F2_C11", "bd" => "idSector")
);

//en este se definen los campos POST obligatorios a completar
$camposRequeridos = array (ALTA => array ( T_CLIENTE => array("F2_C1", "F2_C3", "F2_C4", "F2_C5", "F2_C6", "F2_C7", "F2_C9", "F2_C10", "F2_C11"),
                                             T_SOCIO => array("F2_C1", "F2_C3", "F2_C4", "F2_C5", "F2_C6", "F2_C7", "F2_C9", "F2_C10")),
                           MODI => array ( T_CLIENTE => array("F2_C1", "F2_C5", "F2_C6", "F2_C7", "F2_C9", "F2_C10", "F2_C11"),
                                             T_SOCIO => array("F2_C1", "F2_C5", "F2_C6", "F2_C7", "F2_C9", "F2_C10")),
                           MDTU  => array ( T_CLIENTE => array(),
                                            T_SOCIO => array("F2_C2")),
                                             );

////////////////// LOGICA DE PROCESO /////////////////////////////////////////////////////////////////////////////////////////////////////////

$nivel = chequearConexion();

if ($nivel == ANONIMO) {  //si el usuario es anonimo, la opción es permitir el registro
                         //aunque se llame con parámetros GET, lo ignoro, al no estar conectado, se le intenta registrar.

    if (SESSION("tipoUsuario")) { //si hemos pasado por el registro previo que nos indica el tipo de usuario

	          if (POST("enviar")) {  //si se ha pulsado el boton de dar de alta
                      $errores = array();
                      $camposQueFaltan = array();
	              comprobarCampos($campos,$camposRequeridos[ALTA][SESSION("tipoUsuario")],$camposQueFaltan,$errores,ALTA);
                      if ($errores) mostrarFormUsuario($campos,$camposQueFaltan,$errores,ALTA);
	              else altaUsuario($campos);

	    } elseif (POST("borrar")) {  //si se ha preferido limpiar, se borra todo
		      inicializarCamposPOST($campos);
		      unset($_SESSION["usuarioRG"]);
                      $_POST["F2_C2"]=SESSION("tipoUsuario"); //el tipo de usuario tiene que completarse porque no aparece en el formulario para el alta
                      mostrarFormUsuario($campos,array(),array(),ALTA);

	    } else {
                    if (SESSION("usuarioRG")) cargarCamposPOST($campos,SESSION("usuarioRG"));
                    $_POST["F2_C2"]=SESSION("tipoUsuario"); //el tipo de usuario lo cargamos siempre por si no hay sesión y por seguridad
                    mostrarFormUsuario($campos,array(),array(),ALTA);  //si se ha recargado simplemente la página, se muestran de nuevo los datos
            }

    } else redirigir(PAG_REG_PPAL); //si no se ha pasado previamente por la primera página del registro, lo redigirmos allí directamente

} else { //en esta parte se lleva a cabo el tratamiento para usuarios conectados, que trata la modificación o sólo visualización de los datos

         $error="";
         $idUsuarioPT = "";
         $idUsuarioMD = "";
         $usuarioPT = "";

         //1. comprobamos si hay peticion explícita y que el valor de idUsuario es correcto
         if (GET("usuario")) {
             if (!$error=chequearCampo("idUsuario", GET("usuario"))) $idUsuarioPT = GET("usuario");
         } else {
             $error=ETIQ_USUARIO_REGISTRADO; //si no se pide usuario, es como si fuese un registro, y no es válido para un usuario que está conectado
         }

         //2. si se ha hecho correctamente la petición, comprobamos el nivel de acceso del usuario conectado
         if (!$error) {
                 if (($idUsuarioPT)AND($idUsuarioPT==SESSION_CAMPO("usuario","idUsuario"))) $modo= MODI;  //si el usuario pedido es el conectado, puede hacer cambios en sus propios datos
             elseif ($nivel==ADMON) $modo = MODI; //pero si es administrador, puede hacerlo todo también con otros usuarios
             elseif ($nivel==GESTOR) $modo = MDTU;  //si el usuario a tratar no es uno mismo (no se cumple anterior if), se debe tener rango de gestor mínimo para visualizar los datos y modif el tipo de usuario
               else  $error=ETIQ_USUARIO_NO_AUTORIZADO; //en otro caso no hay privilegios suficientes, se marca error.
         }

        //3. si no hay error, comprobamos que el usuario pedido en verdad exite en la BD
        //aunque parezca una acceso gratuito, si ha ocurrido algo en la base de datos o se ha borrado, esto impide que se siga intentando tratar.
         if (!$error)
             if (!$usuarioPT = Usuario::obtenerPorIdUsuario($idUsuarioPT)) $error=ETIQ_USUARIO_NO_EXISTE;

         //4. si no hay errores, la peticion es correcta, hay privilegios y el usuario existe en la base de datos.
         if (!$error) {

             $idUsuarioMD = SESSION_CAMPO("usuarioMD","idUsuario"); //si hay sesión activa, recuperamos el campo "idUsuario" que se estaba tratando.

             if ($idUsuarioPT == $idUsuarioMD) {//la petición es la misma, continuamos la sesion anterior
      	                   if (POST("enviar")) {  //si se ha pulsado el boton de enviar, se quiere actualizar los datos
                               $errores = array();
                               $camposQueFaltan = array();
                               $tipoUsuario = (SESSION_CAMPO("usuarioMD","tipoUsuario")==T_CLIENTE) ? T_CLIENTE : T_SOCIO;
                               comprobarCampos($campos,$camposRequeridos[$modo][$tipoUsuario],$camposQueFaltan,$errores,$modo);
                               if ($errores) mostrarFormUsuario($campos,$camposQueFaltan,$errores,$modo,$nivel);
                               else { $usuario = crearUsuario($campos,$modo);
                                      if (sonIguales($usuario,$usuarioPT,$campos)) {
                                             $errores[]=ETIQ_REG_IGUALES;
                                             mostrarFormUsuario($campos,$camposQueFaltan,$errores,$modo,$nivel);
                                      } else actualizaUsuario($usuario);
                               }

      	             } elseif ((POST("borrar"))AND(POST("borrar")==ETIQ_BT_CONFIRMAR_BAJA)) {  //se ha confirmado borrar el usuario
                               eliminaUsuario();
                               
      	             } elseif (POST("borrar")) {  //si se pretende eliminar el usuario, antes se pide confirmación
                               $errores = array();
                               $camposQueFaltan = array();
 	                       $errores[]=ETIQ_AVISO_BORRAR;
                               cargarCamposPOST($campos,SESSION("usuarioMD"));
	                       mostrarFormUsuario($campos,$camposQueFaltan,$errores,BAJA,$nivel);

      	             } elseif (POST("empresa")) {  //queremos ir a ver la empresa asociada
	 	               redirigir(PAG_EMPRESAS."?usuario=".SESSION_CAMPO("usuarioMD","idUsuario"));

      	             } elseif (POST("accesos")) {  //ver los accesos del usuario
	 	               redirigir(PAG_LIST_ACC_USU."?usuario=".SESSION_CAMPO("usuarioMD","idUsuario")."&orden=ultAcceso&sentido=desc");

                     } else {  //no se ha pulsado botón (se habrá recargado la página con F5 o venimos de otra página) o se pulsó "cancelar"
                               cargarCamposPOST($campos,SESSION("usuarioMD"));
                               mostrarFormUsuario($campos,array(),array(),$modo,$nivel);
                     }

              } else { //si el usuario pedido no era el de la petición activa (o porque no existía aún), se carga de la base de datos
                       cargarCamposPOST($campos,$usuarioPT);
                       $_SESSION["usuarioMD"]=$usuarioPT;
                       mostrarFormUsuario($campos,array(),array(),$modo,$nivel);

              }

         } else { //y aquí se muestran los errores que se han detectado

                 if ($error==ETIQ_USUARIO_REGISTRADO) informacion($error,INFORMACION,"",PAG_AREA_PERSONAL,ETIQ_LINK_AREA_PERSONAL);
             elseif ($error==ETIQ_USUARIO_NO_AUTORIZADO) informacion($error,AREA_RESTRINGIDA,"",PAG_AREA_PERSONAL,ETIQ_LINK_AREA_PERSONAL);
               else  informacion($error,ERROR);

         }

}

/////////////////// FUNCIONES /////////////////////////////////////////////////////////////////////////////////////////////


//// FUNCIONES DE CHEQUEO PROPIAS


function comprobarUsuario(&$camposQueFaltan, &$errores) {

        if (POST("F2_C1")=="Usuario") {
	   $errores[] = ETIQ_USUARIO_NO_PERMITIDO;
       	   $camposQueFaltan[] = "F2_C1";
       	} elseif (Usuario::obtenerPorUsuario(POST("F2_C1"))) {
	        $errores[] = ETIQ_USUARIO_YA_EXISTE;
       	        $camposQueFaltan[] = "F2_C1";
        }

}

function comprobarPassword(&$camposQueFaltan, &$errores) {

	      if ( (POST("F2_C3")!="") AND
	           (POST("F2_C4")!="") AND
 	           (POST("F2_C3")!= POST("F2_C4")) ) {
	           $errores[] = ETIQ_PASSWORD_DISTINTA;
	           $camposQueFaltan[] = "F2_C3";
	           $camposQueFaltan[] = "F2_C4";
	} elseif ((POST("F2_C3")=="Password") OR (POST("F2_C4")=="Password")) {
	           $errores[] = ETIQ_PASSWORD_NO_PERMITIDA;
	           $camposQueFaltan[] = "F2_C3";
	           $camposQueFaltan[] = "F2_C4";
        }


}

function comprobarEmail(&$camposQueFaltan, &$errores) {

	if (Usuario::obtenerPorEmail(POST("F2_C5"))) {
		$errores[] = ETIQ_EMAIL_YA_EXISTE;
       	        $camposQueFaltan[] = "F2_C5";
        }

}

//////////// FUNCIONES DE TRATAMIENTO



function crearUsuario($campos, $modo) {


        $propiedades = array();

        if ($modo == MDTU) { //en este "modo" solo se cambia el tipo de usuario y al bloquear el resto de campos no se devuelven
                             //por lo que es necesario reponerlos de la variable de sesión conservada
            $nuevoTipoUsuario = POST("F2_C2");
            cargarCamposPOST($campos,SESSION("usuarioMD"));
            $_POST["F2_C2"] = $nuevoTipoUsuario;
        }
        foreach($campos as $campo) $propiedades[$campo["bd"]]=POST($campo["post"]);
        if ($modo == ALTA) $propiedades["idUsuario"]="";
        else $propiedades["idUsuario"]=SESSION_CAMPO("usuarioMD","idUsuario");
        return  new Usuario($propiedades);

}


function altaUsuario($campos) {

	$usuario = crearUsuario($campos,ALTA);
	if (SESSION("tipoUsuario")==T_CLIENTE) {
		$usuario->grabarEnBD();
		unset($_SESSION["usuarioRG"]);
		unset($_SESSION["tipoUsuario"]);
                informacion(ETIQ_REGISTRO_OK_USU,ENHORABUENA);
	} else {
		$_SESSION["usuarioRG"]=$usuario;
	        redirigir(PAG_EMPRESAS);
	}
}

function actualizaUsuario($usuario) {

        $usuario->actualizarEnBD();
        $idUsuario=SESSION_CAMPO("usuarioMD","idUsuario");
        unset($_SESSION["usuarioMD"]);
        informacion(ETIQ_USUARIO_ACTUALIZADO,INFORMACION,"",PAG_USUARIOS."?usuario=".$idUsuario,ETIQ_LINK_VOLVER);

}

function eliminaUsuario() {

        Acceso::borrarDeBD(SESSION_CAMPO("usuarioMD",'idUsuario'));
        if (SESSION_CAMPO("usuarioMD","tipoUsuario")!=T_CLIENTE) {
	    $empresa=Empresa::obtenerPorIdUsuario(SESSION_CAMPO("usuarioMD",'idUsuario'));
	    $archivo = RUTA_ABS.DIR_ARCH_EMP.PREFIJO_ARCH_EMP.$empresa->obtenerCampo("idEmpresa");
	    if (file_exists($archivo.".gif")) unlink($archivo.".gif"); //por si acaso se dejó libre un idEmpresa... raro...
	    if (file_exists($archivo.".jpg")) unlink($archivo.".jpg");
	    $ofertas = Oferta::obtenerOfertasEmpresa($empresa->obtenerCampo("idEmpresa"));
	    foreach($ofertas as $oferta) {
	            $archivo = RUTA_ABS.DIR_ARCH_OFE.PREFIJO_ARCH_OFE.$oferta->obtenerCampo("idOferta");
	            if (file_exists($archivo.".gif")) unlink($archivo.".gif"); //por si acaso se dejó libre un idEmpresa... raro...
	            if (file_exists($archivo.".jpg")) unlink($archivo.".jpg");
	            $oferta->borrarDeBD();
            }
	    $noticias = Noticia::obtenerNoticiasUsuario(SESSION_CAMPO("usuarioMD",'idUsuario'));
	    foreach($noticias as $noticia) $noticia->borrarDeBD();
            $empresa->borrarDeBD();
        }
        $_SESSION["usuarioMD"]->borrarDeBD();
        if ((SESSION_CAMPO("usuarioMD","idUsuario")==SESSION_CAMPO("usuario","idUsuario"))) {
   	    unset($_SESSION["usuarioMD"]);
   	    unset($_SESSION["usuario"]);
            informacion(ETIQ_USUARIO_ELIMINADO,INFORMACION,"",PAG_PPAL,ETIQ_LINK_PAG_PPAL);
        } else {
    	  unset($_SESSION["usuarioMD"]);
  	  if (SESSION("listUsuarios"))
              informacion(ETIQ_USUARIO_ELIMINADO,INFORMACION,"",PAG_LIST_USU,ETIQ_LINK_VOLVER_LIST);
          else
              informacion(ETIQ_USUARIO_ELIMINADO,INFORMACION,"",PAG_AREA_PERSONAL,ETIQ_LINK_AREA_PERSONAL);
        }
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////

// y esta función es la que muestra el formulario que compone la ficha de un usuario
function mostrarFormUsuario($campos, $camposQueFaltan, $errores, $modo=ALTA, $nivel=ANONIMO) {


//primero damos de alta la plantilla y rellenamos títulos y mensajes en la cabecera
        $smarty = nuevaPlantilla();

        if ($modo==ALTA) $tituloPagina = (SESSION("tipoUsuario")==T_CLIENTE) ? ETIQ_TIT_REG_CLIENTE : ETIQ_TIT_REG_SOCIO;
                    else $tituloPagina = ETIQ_TIT_MOD_USU;

        $smarty->assign('tituloPagina',$tituloPagina);

        if (!$errores) $errores[] = ($modo==ALTA) ? ETIQ_SUBTIT_INTRO_DATOS : "";
        else $smarty->assign('class_mensajes',CLAS_ERROR);

        $smarty->assign('mensajes',$errores);

        $smarty->display(TPL_CABECERA);

//rellenamos las opciones de los campos con opciones a elegir

        if (($nivel == ADMON)OR(POST("F2_C2")==T_ADMINISTRADOR)) //si el que modifica es el administrador o se ven los datos de un administrador, se incluye esa clase en la lista
                                                                 //lo que pasa que luego si no se tienen nivel de administrador, esta opcion se bloquea. Solo los administradores
                                                                 //pueden rebajarse de rango o crear otros administradors. Un gestor sólo verá el dato deshabilitado.
             $smarty->assign('opciones_F2_C2',  array("socio" => "Socio",  "premium" => "Premium", "gestor" => "Gestor", "administrador" => "Administrador"));
        else $smarty->assign('opciones_F2_C2',  array("socio" => "Socio",  "premium" => "Premium", "gestor" => "Gestor"));


        $smarty->assign('opciones_F2_C10', array( "H" => "Hombre", "M" => "Mujer"));

        $smarty->assign('opciones_F2_C11', Sector::obtenerListadoDeSectores());
        
//los que corresponden a las password siempre se borran para que no aparezcan en el código
        $_POST["F2_C3"]="";
        $_POST["F2_C4"]="";

//posteriormente rellenamos todas las variables asociadas a campos POST.
//y su clase a "error" si resulta que el campo está entre los que faltan
        foreach ($campos as $campo) {
          	 $smarty->assign($campo["post"],POST($campo["post"]));
          	 $smarty->assign('class_'.$campo["post"],comprobarCampo($campo["post"],$camposQueFaltan));
        }

////////////////////////////en función del modo y el nivel, establecemos los campos y botones a mostrar

//si es un alta o modificación, se muestra la contraseña
        if (($modo==ALTA) OR ($modo==MODI)) {
            $smarty->assign('mostrar_F2_C3',true);
            $smarty->assign('mostrar_F2_C4',true);
        }
        
//si tratamos de un cliente, se muestra el sector para elegir
        if (POST("F2_C2")==T_CLIENTE) {
            $smarty->assign('mostrar_F2_C11',true);
        }

        if ( (($modo==MDTU) OR (($modo==MODI)AND($nivel>=GESTOR))) //si el modo es MDTU (para eso debe ser gestor) o modificacion con rango de administrador...
             AND(POST("F2_C2")!=T_CLIENTE)) // ... y el usuario NO es un cliente, se muetra el tipo de usuario para que GESTORES y ADMINISTRADORES puedan cambiar
            $smarty->assign('mostrar_F2_C2',true);
            
//y si no es un alta, el usuario y el correo electronico siempre se bloquean. NO SE PERMITE MODIFICARLOS. Son datos intrínsecos que conllevan dar de baja el usuario y
//darse de nuevo un alta si quiere modificarlos (como si fuese un usuario nuevo, pues identifican completamente el usuario).
        if ($modo!=ALTA) {
            $smarty->assign('bloquear_F2_C1','readonly="readonly"'); //en modo readonly son devueltos al submitir el formulario
            $smarty->assign('bloquear_F2_C5','readonly="readonly"');
        }

//y ahora los botones y bloqueo de campos...

              if ($modo==ALTA) {  $smarty->assign('mostrar_Enviar',true);
                                  if (SESSION("tipoUsuario")==T_CLIENTE) $smarty->assign('enviar',ETIQ_BT_ALTA);
                                                                    else $smarty->assign('enviar',ETIQ_BT_CONTINUAR);
                                  $smarty->assign('mostrar_Borrar',true);
                                  $smarty->assign('borrar',ETIQ_BT_LIMPIAR);

        } elseif ($modo==MODI) {  $smarty->assign('mostrar_Enviar',true);
                                  $smarty->assign('enviar',ETIQ_BT_ACTUALIZAR_USU);
                                  if (POST("F2_C2")!=T_CLIENTE) {
                                     $smarty->assign('mostrar_Empresa',true);
                                     $smarty->assign('empresa',ETIQ_BT_VER_EMPRESA);
                                  }
                                  if ($nivel==ADMON) { $smarty->assign('mostrar_Accesos',true);
                                                       $smarty->assign('accesos',ETIQ_BT_VER_ACCESOS); }
                                  $smarty->assign('mostrar_Borrar',true);
                                  $smarty->assign('borrar',ETIQ_BT_BAJA);

        } elseif ($modo==MDTU) {  if (POST("F2_C2")!=T_CLIENTE) {
                                      $smarty->assign('mostrar_Empresa',true); //este modo es como VISU (sólo visualizción...
                                      $smarty->assign('empresa',ETIQ_BT_VER_EMPRESA);
                                  }                              
                                  foreach ($campos as $campo) $smarty->assign('bloquear_'.$campo["post"],'disabled="disabled"');
                                  if ((POST("F2_C2")!=T_CLIENTE)&&   //... solo que si el cliente es un socio, premium o gestor, se puede cambiar su tipo, por lo que se quita el bloqueo
                                      (POST("F2_C2")!=T_ADMINISTRADOR)) {
                                      $smarty->assign('bloquear_F2_C2',"");
                                      $smarty->assign('mostrar_Enviar',true);
                                      $smarty->assign('enviar',ETIQ_BT_MOD_TIP_USU);
                                  }

        } elseif ($modo==BAJA) {  $smarty->assign('mostrar_Borrar',true); //modo especial transitorio cuando en MODI lo que se pide es eliminar el usuario. Pide confirmación
                                  $smarty->assign('borrar',ETIQ_BT_CONFIRMAR_BAJA);
                                  $smarty->assign('mostrar_Cancelar',true);
                                  $smarty->assign('cancelar',ETIQ_BT_CANCELAR);
                                  foreach ($campos as $campo) $smarty->assign('bloquear_'.$campo["post"],'disabled="disabled"');
        // cualquier otro caso sería visualización, por lo que se bloquea todo y no se muestra un botón. A no ser que haya un mal modo, a este "ELSE" no llegará
        } else  foreach ($campos as $campo) $smarty->assign('bloquear_'.$campo["post"],'disabled="disabled"');

        if ($modo == ALTA) $smarty->assign('action',completarURL(PAG_USUARIOS));
                     else  $smarty->assign('action',completarURL(PAG_USUARIOS."?usuario=".SESSION_CAMPO("usuarioMD","idUsuario")));

	 $smarty->display(TPL_FICHA_USU);

 	 if ($nivel == ANONIMO) {
     	     $smarty->assign('linkPie',completarURL(PAG_REG_PPAL));
 	     $smarty->assign('textoLinkPie',ETIQ_LINK_VOLVER);
	 } else {
             if (SESSION("listUsuarios")) {
       	         $smarty->assign('linkAlt',completarURL(PAG_AREA_PERSONAL));
 	         $smarty->assign('textoLinkAlt',ETIQ_LINK_AREA_PERSONAL);
                 $smarty->assign('linkPie',completarURL(PAG_LIST_USU));
  	         $smarty->assign('textoLinkPie',ETIQ_LINK_VOLVER_LIST);
             }elseif (SESSION("listEmpresas")) {
       	         $smarty->assign('linkAlt',completarURL(PAG_AREA_PERSONAL));
 	         $smarty->assign('textoLinkAlt',ETIQ_LINK_AREA_PERSONAL);
                 $smarty->assign('linkPie',completarURL(PAG_LIST_EMP));
  	         $smarty->assign('textoLinkPie',ETIQ_LINK_VOLVER_LIST);
             }elseif (SESSION("listAccesos")) {
       	         $smarty->assign('linkAlt',completarURL(PAG_AREA_PERSONAL));
 	         $smarty->assign('textoLinkAlt',ETIQ_LINK_AREA_PERSONAL);
                 $smarty->assign('linkPie',completarURL(PAG_LIST_ACC_GRAL));
  	         $smarty->assign('textoLinkPie',ETIQ_LINK_VOLVER_LIST);
             }elseif (SESSION("listNoticias")) {
       	         $smarty->assign('linkAlt',completarURL(PAG_AREA_PERSONAL));
 	         $smarty->assign('textoLinkAlt',ETIQ_LINK_AREA_PERSONAL);
                 $smarty->assign('linkPie',completarURL(PAG_LIST_NOT));
  	         $smarty->assign('textoLinkPie',ETIQ_LINK_VOLVER_LIST);
             } elseif (($nivel ==NORMAL) AND (POST("F2_C2")==T_CLIENTE)) {
     	         $smarty->assign('linkPie',completarURL(PAG_PPAL));
 	         $smarty->assign('textoLinkPie',ETIQ_LINK_PAG_PPAL);
             } else {
     	         $smarty->assign('linkPie',completarURL(PAG_AREA_PERSONAL));
 	         $smarty->assign('textoLinkPie',ETIQ_LINK_AREA_PERSONAL);
             }
  	 }
	 $smarty->display(TPL_PIE);
}
?>