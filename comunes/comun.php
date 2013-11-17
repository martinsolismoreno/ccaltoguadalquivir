<?php



////// FUNCIONES BASICAS ///////////////////////////////////////

//con esta función comienzan todas las páginas y devuelve el resultado el perfil de quien está llamando la página
function chequearConexion() {

	session_start();

	$pagina = basename($_SERVER["PHP_SELF"]);
      	if ((!SESSION("usuario")) OR
            (!($_SESSION["usuario"] = Usuario::obtenerPorIdUsuario(SESSION_CAMPO("usuario","idUsuario"))))) {

	     if (($pagina != "galeria_noticias.php") AND
                 ($pagina != "galeria_ofertas.php")) {
             	  $acceso = new Acceso( array ("idUsuario" => "9999999",
					  "pagina" => $pagina));
	 	  $acceso->grabarEnBD();
             }
  	     controlSesiones(ANONIMO);
	     return ANONIMO;
	} else {
	     if (($pagina != "galeria_noticias.php") AND
                 ($pagina != "galeria_ofertas.php")) {
		  $acceso = new Acceso( array ("idUsuario" => SESSION_CAMPO("usuario","idUsuario"),
					       "pagina" => $pagina) );
	 	  $acceso->grabarEnBD();
             }
  	     $nivel = $_SESSION["usuario"]->obtenerNivel();
  	     controlSesiones($nivel);
             return $nivel ;
	}

}

//nos da el perfil del usuario
function perfil()  {

        if (!SESSION("usuario")) {
	     return ANONIMO;
	} else {
             return $_SESSION["usuario"]->obtenerNivel();
	}

}

//esta función se llama siempre también al principio y sirve para controlar el uso de las sesiones que activo
function controlSesiones($nivel) {

       $paginaActual = strtolower(basename($_SERVER["PHP_SELF"]));

       if (($paginaActual == PAG_PPAL)&&(isset($_GET["comprobarjs"])))
            unset($_SESSION["nojavascript"]); //cuando salimos de la página principal (o se recarga, chequeamos de nuevo la existencia de javascript

//estas sesiones son las que guardan los datos provisionales mientras se hace un registro, para ir hacia adelante y atrás cuando se pasa
//de un usuario a su empresa o de esta a su vista previa. Los datos son guardados cuando se pasa de página, por si se quiere volver
//pero una vez que se sale de esas páginas señaladas, que son las que manejan y guardan esos datos, las borro
       if ($nivel == ANONIMO) {
           if ((DIR_EJEC.$paginaActual != PAG_REG_PPAL) AND
               (DIR_EJEC.$paginaActual != PAG_USUARIOS) AND
               (DIR_EJEC.$paginaActual != PAG_EMPRESAS) AND
               (DIR_EJEC.$paginaActual != PAG_VPREV_EMP)) {
               unset($_SESSION["tipoUsuario"]);
               unset($_SESSION["usuarioRG"]);
               unset($_SESSION["empresaRG"]);
               unset($_SESSION["archEmpresaRG"]);
               unset($_SESSION["imgEmpresaRG"]);
           }
           
           if (DIR_EJEC.$paginaActual != PAG_RECORDAR) {
               unset($_SESSION["recordar"]);
           }
//estas son similares a las del registro, pero para las actualizaciones de los datos, por lo que son para usuarios que están registrados
      } else {
           if ((DIR_EJEC.$paginaActual != PAG_USUARIOS) AND
               (DIR_EJEC.$paginaActual != PAG_EMPRESAS) AND
               (DIR_EJEC.$paginaActual != PAG_VPREV_EMP)) {
               unset($_SESSION["usuarioMD"]);
               unset($_SESSION["empresaMD"]);
               unset($_SESSION["archEmpresaMD"]);
               unset($_SESSION["imgEmpresaMD"]);
           }
           
           if (DIR_EJEC.$paginaActual != PAG_OFERTAS) {
               unset($_SESSION["oferta"]);
               unset($_SESSION["archOferta"]);
               unset($_SESSION["imgOferta"]);
           }
           
           if (DIR_EJEC.$paginaActual != PAG_NOTICIAS) {
               unset($_SESSION["noticia"]);
           }

//y por otro lado tenemos otras variables que actúan como "bandera" para indicar que se ha pasado por un listado para escoger un
//usuario, empresa, oferta... de manera que desde las páginas que tratan esos elementos, se pueda volver a él.
//normalemente son puestas a falso cuando se parte de nuevo desde el área personal
                 if (DIR_EJEC.$paginaActual == PAG_AREA_PERSONAL) {
                     $_SESSION["listUsuarios"]=false;
                     $_SESSION["listAccesos"]=false;
                     $_SESSION["listEmpresas"]=false;
                     $_SESSION["listEmpPte"]=false;
                     $_SESSION["listOfertas"]=false;
                     $_SESSION["listOfeEmp"]=false;
                     $_SESSION["listNoticias"]=false;
                     $_SESSION["listNotUsu"]=false;
           } elseif (DIR_EJEC.$paginaActual == PAG_LIST_USU) $_SESSION["listUsuarios"]=true;
             elseif (DIR_EJEC.$paginaActual == PAG_LIST_EMP) $_SESSION["listEmpresas"]=true;
             elseif (DIR_EJEC.$paginaActual == PAG_LIST_ACC_GRAL) $_SESSION["listAccesos"]=true;
             elseif (DIR_EJEC.$paginaActual == PAG_LIST_PTE) $_SESSION["listEmpPte"]=true;
             elseif (DIR_EJEC.$paginaActual == PAG_LIST_OFE) $_SESSION["listOfertas"]=true;
             elseif (DIR_EJEC.$paginaActual == PAG_LIST_OFE_EMP) $_SESSION["listOfeEmp"]=true;
             elseif (DIR_EJEC.$paginaActual == PAG_LIST_NOT) $_SESSION["listNoticias"]=true;
             elseif (DIR_EJEC.$paginaActual == PAG_LIST_NOT_USU) $_SESSION["listNotUsu"]=true;
       }

       if (DIR_EJEC.$paginaActual == PAG_LIST_OFE) {
                     $_SESSION["listOfeEmp"]=false;
       }

}

//esta función es común a todas lás páginas que tienen como cabecera la del portal principal
//con todo el tratamiento de si hay javascript o no lo hay y demás
function cabeceraPrincipal($nivel) {

          if ($nivel!=ANONIMO) $nombreUsuario = $_SESSION["usuario"]->obtenerCampo("nombre");
          else  $nombreUsuario = "";

          $smarty = nuevaPlantilla();

          $smarty->assign('javascript',comprobarJavascript());

          $smarty->assign('nivel',$nivel);
          $smarty->assign('nombreUsuario',$nombreUsuario);

          $smarty->assign('link_logo',completarURL(PAG_PPAL));
          $smarty->assign('link_conectar',completarURL(PAG_CONEX));
          $smarty->assign('link_desconectar',completarURL(PAG_DECONEX));
          $smarty->assign('link_cuenta',completarURL(PAG_AREA_PERSONAL));
          $smarty->assign('link_recordar',completarURL(PAG_RECORDAR));
          $smarty->assign('link_registro',completarURL(PAG_REG_PPAL));

          $smarty->assign('empresas',completarURL(PAG_PPAL_EMPRESAS));
          $smarty->assign('ofertas',completarURL(PAG_PPAL_OFERTAS));
          $smarty->assign('noticias',completarURL(PAG_PPAL_NOTICIAS));
          $smarty->assign('asociacion',completarURL(PAG_PPAL_ASOCIACION));
          $smarty->assign('normativa',completarURL(PAG_PPAL_NORMATIVA));

          $paginaActual = strtolower(basename($_SERVER["PHP_SELF"]));
              if ($paginaActual==PAG_PPAL_EMPRESAS)   $smarty->assign('clase_menuE',"selected");
          elseif ($paginaActual==PAG_PPAL_OFERTAS)    $smarty->assign('clase_menuO',"selected");
          elseif ($paginaActual==PAG_PPAL_NOTICIAS)   $smarty->assign('clase_menuN',"selected");
          elseif ($paginaActual==PAG_PPAL_ASOCIACION) $smarty->assign('clase_menuA',"selected");


          if (($paginaActual==PAG_PPAL) OR 
	      ($paginaActual==PAG_PPAL_ASOCIACION) OR 
              ($paginaActual==PAG_PPAL_NORMATIVA)) {

             if (!isset($_SESSION["nojavascript"])) {
                 $paginaActual = strtolower(basename($_SERVER["PHP_SELF"]));
                 $parametros="";
                 foreach($_GET as $clave => $valor) {
                         $parametros.="&amp;$clave";
                         $parametros.= ($valor) ? "=$valor":"";
                 }

                $smarty->assign('metanoscript','<noscript><meta http-equiv="refresh" content="1;url='.$paginaActual.'?nojavascript'.$parametros.'"/></noscript>');

             } else {

                $smarty->assign('metarefresh','<meta http-equiv="refresh" content="30"/>');
             }

	  }

          $smarty->display(TPL_CABECERA_PPAL);

}

function mostrar_buscador($action, $leyenda, $etiqBoton="", $sector=0, $poblacion=0) {

        $smarty = nuevaPlantilla();

        $smarty->assign('action',completarURL($action));

        if ($etiqBoton) $smarty->assign('boton_buscar',$etiqBoton);

        $sectores = Sector::obtenerListadoDeSectores();
        $sectores[0] = "-- cualquier sector --";
        $smarty->assign('opciones_sector', $sectores);

        $poblaciones =  Poblacion::obtenerListadoDePoblaciones();
        $poblaciones[0] = "-- toda la comarca --";
        $smarty->assign('opciones_poblacion', $poblaciones);

        $smarty->assign('sector',$sector);

        $smarty->assign('poblacion',$poblacion);

        $smarty->assign('leyenda',$leyenda);

        $smarty->display(TPL_BUSCADOR);

}

function comprobarJavascript() {

     if (!isset($_SESSION["nojavascript"])) { if (isset($_GET["nojavascript"])) $_SESSION["nojavascript"] = true; } ;

     return (isset($_SESSION["nojavascript"])) ? false : true;

}

//completa una URL con el ID de session (cuando no hay cookies)
//detecta si es necesario añadirle una ? o un & según si la URL ya lleva contenido GET
function completarURL($url) {

         if (preg_match("/[\?]/",$url)) {
	    if (SID) return RAIZ.$url."&amp;".SID;
        	     return RAIZ.$url;
         } else {
	    if (SID) return RAIZ.$url."?".SID;
          	     return RAIZ.$url;
         }

}

function completarURLBis($url) {

         if (preg_match("/[\?]/",$url)) {
	    if (SID) return RAIZ.$url."&".SID;
        	     return RAIZ.$url;
         } else {
	    if (SID) return RAIZ.$url."?".SID;
          	     return RAIZ.$url;
         }

}

//redirige una URL completándola antes con su ID de sessión si es necesario
function redirigir($url) {
         $urlc = completarURLBis($url);
         header("Location: $urlc");
}


//además de crear la plantilla, define los directorios de funcionamiento
//he preferido hacerlo así en vez del archivo php.ini para evitar confusiones
//con plantillas de otras aplicaciones, según recomendaciones.

function nuevaPlantilla() {

	 $smarty = new Smarty;

	 $smarty->template_dir = SMARTY_TEMPLATE_DIR;
	 $smarty->compile_dir  = SMARTY_COMPILE_DIR;
	 $smarty->config_dir   = SMARTY_CONFIG_DIR;
	 $smarty->cache_dir    = SMARTY_CACHE_DIR;

//se define aquí siempre el link de la cabecera por si hay que completar la dirección con el ID de Sesion
    	 $smarty->assign('linkCabecera',completarURL(PAG_PPAL));

         return $smarty;

}


/////////////// FUNCIONES PARA CHEQUEAR CAMPOS SEGÚN EL PATRON REQUERIDO PARA LOS CAMPOS DE LA BD


// función general de chequeo de los campos de formularios de la aplicación
// se le añaden algunas funciones específicas que se mantienen en las páginas que manejan
// esos campos, pero que se da de alta aquí el realizar el chequeo en función del nombre del campo
function comprobarCampos($campos,$camposRequeridos,&$camposQueFaltan,&$errores,$modo=ALTA) {

        $camposQueFaltan = comprobarCamposRequeridos($camposRequeridos);
	if ($camposQueFaltan) $errores[] = ETIQ_COMPLETAR_CAMPOS;
        foreach($campos as $campo) {
                quitarEspacios($_POST[$campo["post"]]); //se modifica su valor por referencia
                if ((($modo == ALTA) AND (POST($campo["post"]))) OR
                    ((POST($campo["post"])))) {
  	           $error = chequearCampo($campo["bd"],POST($campo["post"]));
  	           if ($error) {
                       $errores[] = $error;
                       $camposQueFaltan[] = $campo["post"];
                   } else {
                   //estos son chequeos adicionales que se dan de alta aquí,
                   //pero las funciones están definidas en su página correspondiente.
                   //los parámetros también son modificados por referencia
                       if ($modo == ALTA) { //estos solo se comprueban en el alta porque no se pueden modificar una vez registrados
                           if ($campo["post"]=="F2_C1") comprobarUsuario($camposQueFaltan, $errores);  //se modifican por referencia
                           if ($campo["post"]=="F2_C5") comprobarEmail($camposQueFaltan, $errores);
                           if ($campo["post"]=="F3_C1") comprobarDNI($camposQueFaltan, $errores);
                       }
                       if ($campo["post"]=="F2_C4") comprobarPassword($camposQueFaltan, $errores);
                       if ($campo["post"]=="F4_C4") comprobarFechaIni($camposQueFaltan, $errores);
                       if ($campo["post"]=="F4_C5") comprobarFechaFin($camposQueFaltan, $errores);
                       if ($campo["post"]=="F5_C5") comprobarFechaNot($camposQueFaltan, $errores);
                   }
                }
        }
}

//chequea un valor según la clave (tipo de campo) que se le pasa
//si cuadra con la expresión regular establecidad para la clave, devuelve la cadena vacía
//ya que en otro caso devuelve una cadena informando de las condiciones que debe cumplir
function chequearCampo($clave, $valor) {

         if (!array_key_exists($clave, $GLOBALS["camposBD"])) return ETIQ_CAMPO_SIN_EXREG_ASOCIADA;

         if ($error = chequearLongCampo($clave,$valor)) return $error;
         elseif (preg_match($GLOBALS["camposBD"][$clave]["exreg"], $valor)) return "";
           else  return $GLOBALS["errores_exreg"][$clave];

}

//chequea un valor según la clave (tipo de campo) que se le pasa
//si cuadra con la expresión regular establecidad para la clave, devuelve la cadena vacía
//ya que en otro caso devuelve una cadena informando de las condiciones que debe cumplir
function chequearLongCampo($clave, $valor) {

         if (!array_key_exists($clave, $GLOBALS["camposBD"])) return ETIQ_CAMPO_SIN_EXREG_ASOCIADA;

         if ((strlen($valor)<$GLOBALS["camposBD"][$clave]["lmin"]) OR
             (strlen($valor)>$GLOBALS["camposBD"][$clave]["lmax"]))
             return $GLOBALS["errores_longitud"][$clave];
         else
             return "";   
             
}


//comprueba si las variables POST que se requieren en el array del parámetro tienen valor distinto de vacío
//devolviendo un array con aquellas que no existen o están vacías
function comprobarCamposRequeridos($camposRequeridos) {
	$camposNecesarios = array();
	foreach($camposRequeridos as $campo)
		if (!POST($campo)) $camposNecesarios[] = $campo;
	return $camposNecesarios;
}


//modifica el campo que se pasa (por referencia) eliminándole los espcios en blanco al principio y al final
//y si hay más de uno entre palabras, lo deja a uno
function quitarEspacios(&$campo) {
  	  $campo = preg_replace("/\s{2,}/"," ",$campo);
    	  $campo = preg_replace("/^\s/","",$campo);
    	  $campo = preg_replace("/\s$/","",$campo);
}

//compara los campos indicados de dos objetos
function sonIguales($objeto1, $objeto2, $campos) {

            foreach($campos as $campo)
                    if ($objeto1->obtenerCampo($campo["bd"])!= $objeto2->obtenerCampo($campo["bd"])) return false;
            return true;

}

////// FUNCIONES TRATAMIENTO VARIABLES POST, GET Y SESSION ///////////////////////////////////////

//ayudan a no tener que estar siempre comprobando si el campo está completo, indicando con una cadena
//vacía si no lo está, que para mis tratamientos es lo mismo, ya que si el campo está a cadena vacía,
//es como si no estuviese relleno

//nos devuelve el valor de una variable POST si existe, o la cadena vacía
function POST($campo) {
	 return (isset($_POST[$campo])) ? $_POST[$campo] : "";
}

//nos devuelve el valor de una variable GET si existe, o la cadena vacía
function GET($campo) {
	 return (isset($_GET[$campo])) ? $_GET[$campo] : "";
}

//nos devuelve el valor de una variable de sesión si existe. (puede ser de cualquier tipo)
function SESSION($sesion) {
	 return (isset($_SESSION[$sesion])) ? $_SESSION[$sesion] : "";
}

//nos devuelve el valor de algun campo si la variable de sesion es un objeto (lo más normal)
function SESSION_CAMPO($sesion, $campo) {
	 return (isset($_SESSION[$sesion])&&($_SESSION[$sesion]!="")) ? $_SESSION[$sesion]->obtenerCampo($campo) : "";
}

//nos devuelve el valor de alguna clave si la variable de sesion es un array asociativo
function SESSION_CLAVE($sesion, $clave) {
	 return (isset($_SESSION[$sesion])&&($_SESSION[$sesion]!="")&&(array_key_exists($clave,$_SESSION[$sesion]))) ? $_SESSION[$sesion][$clave] : "";
}

////// FUNCIONES QUE ME FACILITAN EL TRATAMIENTO DE LOS CAMPOS DE UN FORMULARIO ///////////////////


//inicializa variables $_POST de un formulario dadas en el array del parámetro
function inicializarCamposPOST($camposAinicializar) {
	foreach($camposAinicializar as $campo)
		$_POST[$campo["post"]]="";
}

//carga variables POST de un formulario a partir de un objeto guardado
function cargarCamposPOST($campos,$objeto) {
	foreach($campos as $campo)
 	        $_POST[$campo["post"]]=$objeto->obtenerCampo($campo["bd"]);
}

//las siguientes funciones tienen como misión devolver una cadena con el tipo de clase que quiero aplicar
//a los campos de un formulario según existan o no.

//esta función asignará una clase error a aquellos campos que estén en el array ya que serán los que faltan
//me permite señarlarlos en el formulario
function comprobarCampo($campo, $camposQueFaltan) {
	if (in_array($campo, $camposQueFaltan)) return CLAS_ERROR;
}


//función para chequear los valores GET de una petición
//$GET debe ser la variable $_GET que se pide
//$parametrosPermitidos son los valores permitidos y por defecto que se establecen 
function chequearGET($GET,$parametrosPermitidos) {
         $parametros=array();
         foreach($GET as $parametro => $valor)
            if (array_key_exists($parametro,$parametrosPermitidos)) //si el parametro GET está entre los permitidos...
                 if (is_array($parametrosPermitidos[$parametro]))  //si el valor de ese parametro es un grupo de valores (array), comprobamos si caza
                        $parametros[$parametro] =(array_search($valor,$parametrosPermitidos[$parametro])!==false) ? $valor : "";
                elseif ($parametrosPermitidos[$parametro]) $parametros[$parametro] = (!chequearCampo($parametrosPermitidos[$parametro],$valor)) ? $valor : "";
                  else  $parametros[$parametro] = "";
         return $parametros;
}

//chequea los parámetros GET con el array $parámetrosConsulta donde se reflejan aquellos
//que afectan a la consulta y si son obligatorios o no
//devuelve true si se requiere una nueva consulta o false en caso contrario o error
//la variable error se modifica por referencia para dejar constancia del mensaje de error.
function chequearParametros($parametros, $parametrosConsulta, $nombreSesion, &$error) {

         foreach($parametrosConsulta as $clave => $valor) {
             if ((array_key_exists($clave,$parametros) AND (!$parametros[$clave]))) {
                  $error = ETIQ_MAL_LLAMADA;
                  return false;
             }
         }

         $nuevaConsulta = false;
         foreach($parametrosConsulta as $clave => $valor) {
                 if (!SESSION_CLAVE($nombreSesion,$clave)) { //si aun no se ha pedido ese parámetro...
                      if (!array_key_exists($clave,$parametros)) { //.. y no se ha pedido...
                           if (!$parametrosConsulta[$clave]) {  //... y no tiene valor por defecto -> error, se requiere pedirlo
                                $error= ETIQ_MAL_LLAMADA;
                           } else {
                               $_SESSION[$nombreSesion][$clave] = $parametrosConsulta[$clave];   //pero si tiene valor por defecto, se le asigna
                               $nuevaConsulta = true;                                               //y es como nueva consulta (esto se da la primera vez...)
                           }
                      } else {   //si se ha fijado el parámetro y no estaba previamente, también se la asigna, dando nueva consulta
                               $_SESSION[$nombreSesion][$clave] = $parametros[$clave];
                               $nuevaConsulta = true;
                      }
                 } else {  //en el caso que ya anteriormente se había pasado el parámetro, si se pide de nuevo y es distinto, se asigna el nuevo y resulta una nueva consulta...
                      if ((array_key_exists($clave,$parametros))AND($parametros[$clave]!=SESSION_CLAVE($nombreSesion,$clave))) {
                          $_SESSION[$nombreSesion][$clave] = $parametros[$clave];
                          $nuevaConsulta = true;
                      }
                 }
         }
         return $nuevaConsulta;
}


//esta función le da un aray con formato array ("orden" => orden, "sentido" = sentido)
//y otro que tendrá dadas de alta las subordenaciones a seguir en función de la elegida
function ordenar($ordenacion,$subordenaciones) {

         $cadOrdenacion = array();
         $cadOrdenacion[] = array("orden" => $ordenacion["orden"], "sentido" => $ordenacion["sentido"]);
         if (array_key_exists($ordenacion["orden"],$subordenaciones)) {
             foreach ($subordenaciones[$ordenacion["orden"]] as $suborden)
                      $cadOrdenacion[] = array("orden" => $suborden, "sentido" => $ordenacion["sentido"]);
             return $cadOrdenacion;
         } else return $cadOrdenacion;

}

//a partir de un array de arrays "orden" y "sentido", lo transforma en una cadena de texto para el  SELECT
function crearCadOrdenacion($ordenacion) {

           $cadOrdenacion = "";
           foreach($ordenacion as $orden) {
                if ((array_key_exists("orden",$orden))AND(array_key_exists("sentido",$orden))) {
                      if ($cadOrdenacion) $cadOrdenacion .= ", ";
                      else $cadOrdenacion .= " ORDER BY ";
                      $cadOrdenacion .= $orden["orden"];
	              $cadOrdenacion .= (strtolower($orden["sentido"]) == "desc") ? " desc" : " asc";
               }
           }
           return $cadOrdenacion;
}

////////////////////////// FUNCIONES PARA EL TRATAMIENTO DE SUBIDA DE ARCHIVOS

//funcion que prepara el nombre del directorio temporal en función de la ip de conexion y lo devuelve
//al ser por ip y por lo que expongo más abajo al obtener la dirección, impido que desde una dirección
//se suban indiscriminadamente archivos y yo no tengo que llevar la cuenta. Por cada archivo que intente subir
//yo machaco el anterior y si este llega a buen puerto al dar el alta, ya lo traspasaré con un nombre prefijado
//al directorios donde se almacenan los logotipos o las ofertas de una empresa.
function directorio() {

	chdir(RUTA_ABS.DIR_TMP_ARCH);
	$ip = obtenerIP();
	if (!file_exists($ip)) mkdir($ip);
	chdir(RETORNO_DIR_EJEC);
	return RUTA_ABS.DIR_TMP_ARCH.$ip."/";
}

function obtenerIP() {

        // REMOTE_ADDR: dirección ip del cliente
        // HTTP_X_FORWARDED_FOR: si no está vacío indica que se ha utilizado un proxy. 
	// Al pasar por el proxy lo que hace éste es poner su dirección IP como REMOTE_ADDR 
	// y añadir la que estaba como REMOTE_ADDR al final de esta cabecera. 
	// En el caso de que la petición pase por varios proxys cada uno repite la operación, 
	// por lo que tendremos una lista de direcciones IP que partiendo del REMOTE_ADDR original 
	// irá indicando los proxys por los que ha pasado.

	// con esas combinaciones tenemos
	// proxys transparentes
	// REMOTE_ADDR = IP-proxy
	// HTTP_X_FORWARDED_FOR = IP-cliente

	// Proxies anónimos
	// REMOTE_ADDR = IP-proxy
	// HTTP_X_FORWARDED_FOR = IP-proxy

	// Proxys ruidosos
	// REMOTE_ADDR = IP-proxy
	// HTTP_X_FORWARDED_FOR = IP-aleatoria

	// Proxys de alta anonimicidad
	// REMOTE_ADDR = IP-proxy
	// HTTP_X_FORWARDED_FOR = sin-determinar

	//mi intencion es evitar que se suban ficheros con conexiones desde proxy para evitar ataques, ya que con la ip
	//un ordenador tendría que estar renovándola para ir metendiendo muchos ficheros
	//de todas formas desde proxy algo tiene que "envolver" el fichero porque dar error de que no es ni gif ni jpeg,
	//su tipo es application/octet-stream, aunque si se permitiese, subiría la imagen bien.

       if(isset($_SERVER)) {
          if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		return $_SERVER['HTTP_X_FORWARDED_FOR'];
	  } else {
	        if (!empty($_SERVER['REMOTE_ADDR'])) return $_SERVER['REMOTE_ADDR'];
		     			     else    return '0.0.0.0';
	  }
       } else
          if (getenv('HTTP_X_FORWARDED_FOR')) {
		return getenv('HTTP_X_FORWARDED_FOR');
          } else  {
		if (getenv('REMOTE_ADDR')) return getenv( 'REMOTE_ADDR' );
	                             else  return '0.0.0.0';
       }		

}


/////////////////////////////////////////////////////////////////////////


//esta función se suele llamar siempre al principio de cualquier página para informar si
//las opciones no son correctas, la página está restringida, etc... y se evita mostrar la página requerida
//si no se cumplen las condiciones necesarias
//tiene una configuración básica para evitar tener que informarle muchos parámetros
function informacion($mensaje, $tipoPag=INFORMACION, $subtitulo="", $linkPie="", $textoLinkPie="", $linkAlt="", $textoLinkAlt="") {

	 $smarty = nuevaPlantilla();

         $smarty->assign('tituloPagina',$tipoPag);

         $mensajes=array();
	   if (!$subtitulo)
                    if ($tipoPag==ERROR) $mensajes=ETIQ_SUBTIT_ERROR;
                elseif ($tipoPag==ERRORBD) $mensajes=ETIQ_SUBTIT_ERRORBD;
                elseif ($tipoPag==AREA_RESTRINGIDA) $mensajes=ETIQ_SUBTIT_AREA_RESTRINGIDA;
                elseif ($tipoPag==ENHORABUENA) $mensajes=ETIQ_SUBTIT_ENHORABUENA;
                elseif ($tipoPag==HASTA_PRONTO) $mensajes=ETIQ_SUBTIT_HASTA_PRONTO;
                  else $mensajes=ETIQ_SUBTIT_INFORMACION;
         else $mensajes=$subtitulo;

	 $smarty->assign('mensajes',$mensajes);

 	 if (($tipoPag==ERROR) OR ($tipoPag==ERRORBD) OR ($tipoPag==AREA_RESTRINGIDA)) {
             $smarty->assign('class_mensajes',CLAS_ERROR);
             $smarty->assign('class_mensajeCentral',CLAS_ERROR);
         }

 	 $smarty->display(TPL_CABECERA);

	 $smarty->assign('mensajeCentral',$mensaje);

	 $smarty->display(TPL_MENSAJE);

       	 if ((!$linkAlt)OR(!$textoLinkAlt)) {
               $linkAlt="#";
               $textoLinkAlt="";
         }
         $smarty->assign('linkAlt',completarURL($linkAlt));
 	 $smarty->assign('textoLinkAlt',$textoLinkAlt);

 	 if ((!$linkPie)OR(!$textoLinkPie)) {
              $linkPie=PAG_PPAL;
              $textoLinkPie=ETIQ_PAG_PPAL;
         }
         $smarty->assign('linkPie',completarURL($linkPie));
 	 $smarty->assign('textoLinkPie',$textoLinkPie);

	 $smarty->display(TPL_PIE);

}


//función especial usada en las clases de objetos de la base de datos que se llama
//cuando hay un error en la base de datos, por lo que después llama a die() para abortar
//el flujo y después de informar remitir al usuario a la página principal
function errorbd($mensaje) {

informacion($mensaje,ERRORBD);
die();

}

///////////////////////////////////////////////////////////////////////////////////////////////////

?>