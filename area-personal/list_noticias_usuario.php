<?php

require_once("../comunes/config.php");

// arrays que definen los campos que se van a mostrar en las cabeceras de la tablas y su correspondencia con el campo de bd que van a mostrar
$campos = array( array( "casilla" => "A",             "bd" => "activa"),
                 array( "casilla" => "Fecha Noticia", "bd" => "fechaNot"),
                 array( "casilla" => "Noticia",       "bd" => "titular"),
);
//se definen los parametros que se permiten por GET (primera clave) y por cada uno un aray con una clave "valores"
//si "valores" es un array, es la enumeraci�n de los valores permitidos
//si "valores" es una cadena, puede ser vac�a, lo que indica que NO se pide valor, solo que indique el par�metro
//y en otro caso la cadena de "valores" debe ser una expresi�n regular con la que se intentar� cazar el valor del par�metro de entrada.
//en "omision" se indicar� el valor que se toma por defecto si se indica el par�metro pero su valor no se especifica o es erroneo
//cuando "valores" es un array, normalemente un valor del array o la cadena vac�a
//si "valores" es una expresi�n regular, un valor que casa con ella, por ejemplo un n�mero entero por defecto
//si "valores" es la cadena vac�a da igual "omisi�n", s�lo se espera de ese par�metro la cadena vac�a.
$parametrosPermitidos = array ("usuario"   => "idUsuario",
                               "orden"     => array("titular", "usuario", "fechaNot", "activa"),
                               "sentido"   => array("asc", "desc"),
                               "pagina"    => "numVisitas",  //se le impone como condicion un n�mero como por ejemplo el numVistas de la tabla Accesos;
                               "inicio"    => "",
                               "final"     => "",
                               "siguiente" => "",
                               "anterior"  => ""
                 );

//en este array definimos los campos que determinan un cambio en la consulta (nueva llamada a la BD) 
//el primer campo es una clave, subgrupo del anterior array
//el segundo el valor por omisi�n en caso que no est� especificado, la cadena vac�a si es obligatorio especificarlo
//por lo menos la primera vez que se entra, ya que si no, dar� un error de mal llamada.
$parametrosConsulta = array("usuario" => "",
                            "orden"   => "fechaNot",
                            "sentido" => "desc");

//si damos de alta una subordenaci�n, antes de la consulta completamos con el orden de los dem�s campos de la tabla en funci�n del que se
//elige para ordenar
$subordenaciones = array ("titular"  => array ("fechaNot", "activa"),
                          "fechaNot" => array ("titular", "activa"),
                          "activa"   => array ("fechaNot", "titular")
                         );



////////////////// LOGICA DE PROCESO /////////////////////////////////////////////////////////////////////////////////////////////////////////

$nivel = chequearConexion();

//no se actualiza la variables de sesion "listados", porque desde este listado no se puede ir a ning�n lada nada m�s que a limpiar el historial

if ($nivel >= GESTOR) {  //s�lo pueden acceder a los listados de accesos el administrador

         $parametros = chequearGET($_GET,$parametrosPermitidos);

         //tanto el gestor y el administrador tienen los mismo privilegios aqu�, no tenemos que tener en cuenta el nivel

         $error="";
         if (chequearParametros($parametros, $parametrosConsulta, "paramConNotUsu", $error)) {
             $cadOrdenacion = ordenar(SESSION("paramConNotUsu"),$subordenaciones);
             $usuario = SESSION_CLAVE("paramConNotUsu","usuario");
             if (!$_SESSION["ConNotUsu"]=Consulta::obtenerConsultaNoticias($usuario,$cadOrdenacion,TAM_PAG_ACC)) $error=ETIQ_CONSULTA_FALLIDA;
             $parametros["inicio"]=""; //damos de alta el par�metro "inicio" para que se muestre la primera p�gina si no hay error...
         } else if (!$error) SESSION("ConNotUsu")->actualizarPag();

         if ((!$error) AND (!SESSION("ConNotUsu")->totReg())) $error=ETIQ_NO_HAY_REGISTROS;

         // si no hay errores, la peticion es correcta, hay privilegios y la consulta se ha establecido o ya se hab�a creado... se muestran los datos...
         if (!$error) {

                         if (array_key_exists("inicio",$parametros))    listadoFormulario(SESSION("ConNotUsu")->pagInicial(),$campos);
		     elseif (array_key_exists("pagina",$parametros))    listadoFormulario(SESSION("ConNotUsu")->irPag($parametros["pagina"]),$campos);
		     elseif (POST("pagina"))                            listadoFormulario(SESSION("ConNotUsu")->irPag(POST("pagina")),$campos); //la p�gina se ha pedido por el formulario mostrado
		     elseif (array_key_exists("final",$parametros))     listadoFormulario(SESSION("ConNotUsu")->pagFinal(),$campos);
                     elseif (array_key_exists("anterior",$parametros))  listadoFormulario(SESSION("ConNotUsu")->pagAnterior(),$campos);
		     elseif (array_key_exists("siguiente",$parametros)) listadoFormulario(SESSION("ConNotUsu")->pagSiguiente(),$campos);
                       else                                             listadoFormulario(SESSION("ConNotUsu")->actualizarPag(),$campos); //si no se indic� ning�n par�metro de pagina, anterior, etc...
                                                                                                                                      //por lo que si hab�a consulta ya, se refresca la p�gina nada m�s

        } else { //y aqu� se muestran los errores que se han detectado

              if ($error == ETIQ_USUARIO_NO_AUTORIZADO) informacion($error,AREA_RESTRINGIDA,"",PAG_AREA_PERSONAL,ETIQ_LINK_AREA_PERSONAL);
              elseif ($error == ETIQ_NO_HAY_REGISTROS) informacion($error,INFORMACION,"",PAG_AREA_PERSONAL,ETIQ_LINK_AREA_PERSONAL);
              else informacion($error,ERROR,"",PAG_AREA_PERSONAL,ETIQ_LINK_AREA_PERSONAL);
        }

} else { //si no tiene suficiente nivel, le damos mensaje de error en funci�n del nivel del usuario

         if ($nivel==ANONIMO) informacion(ETIQ_ACCESO_RESERVADO,AREA_RESTRINGIDA,"",PAG_REG_PPAL,ETIQ_LINK_REG,PAG_CONEX,ETIQ_BT_CONECTAR);
         else informacion(ETIQ_USUARIO_NO_AUTORIZADO,AREA_RESTRINGIDA,"",PAG_AREA_PERSONAL,ETIQ_LINK_AREA_PERSONAL);
}

/////////////////// FUNCIONES /////////////////////////////////////////////////////////////////////////////////////////////


function listadoFormulario($datos, $campos) {

        $smarty = nuevaPlantilla();
        $usuario = Usuario::obtenerPorIdUsuario(SESSION_CLAVE("paramConNotUsu","usuario"));
        $nick = $usuario->obtenerCampo("usuario");
        $smarty->assign('tituloPagina',ETIQ_TIT_LIST_NOTUSU.'"'.$nick.'"');

        $mensajes=array();
        $mensajes[]="Noticias de la ".SESSION("ConNotUsu")->posIniPag().
                    " a la ".SESSION("ConNotUsu")->posFinPag().
		    " (de ".SESSION("ConNotUsu")->totReg().")";
	$smarty->assign('mensajes',$mensajes);

        $smarty->display(TPL_CABECERA);

    	$smarty->assign('nombreTabla',"tablaNoticiasUsuario");
    	$smarty->assign('numFilas',count($datos));
    	$smarty->assign('numColumnas',count($campos));

        for($c=0; $c<count($campos); $c++) {
            $sentido="asc";
            if (SESSION_CLAVE("paramConNotUsu","orden") == $campos[$c]["bd"]) {
                $sentido = (SESSION_CLAVE("paramConNotUsu","sentido") == "asc") ? "desc" : "asc";
                if ($sentido=="asc")
                    $smarty->assign('class_link_th'.$c,'class="ordenDesc"');
                else
                    $smarty->assign('class_link_th'.$c,'class="ordenAsc"');
            }
       	    $smarty->assign('link_th'.$c,completarURL(PAG_LIST_NOT_USU."?orden=".$campos[$c]["bd"]."&sentido=".$sentido));
       	    if ((SESSION_CLAVE("paramConNotUsu","orden")=="activa")&&($campos[$c]["casilla"]=="A")) $smarty->assign('dato_th'.$c,"");
       	    else $smarty->assign('dato_th'.$c,$campos[$c]["casilla"]);
       	}
        for($f=0; $f<count($datos); $f++) {
            for($c=0; $c<count($campos); $c++) {
                if ($campos[$c]["bd"] == "titular") {
                    $smarty->assign('link_td'.$f.$c,'<a href="'.completarURL(PAG_NOTICIAS."?noticia=".$datos[$f]["idNoticia"]).'">');
                }
                if ($campos[$c]["bd"] == "activa") {
                       $d = ($datos[$f][$campos[$c]["bd"]]) ? "&nbsp;" : "D";
                       $smarty->assign('dato_td'.$f.$c,$d);
                } else $smarty->assign('dato_td'.$f.$c,$datos[$f][$campos[$c]["bd"]]);
       	    }
        }

      	$smarty->assign('pagAct',SESSION("ConNotUsu")->pagAct());
 	$smarty->assign('totPag',SESSION("ConNotUsu")->totPag());
	$smarty->assign('totReg',SESSION("ConNotUsu")->totReg());

        $smarty->assign('link_ini',completarURL(PAG_LIST_NOT_USU."?inicio"));
        $smarty->assign('link_ant',completarURL(PAG_LIST_NOT_USU."?anterior"));
        $smarty->assign('link_sgt',completarURL(PAG_LIST_NOT_USU."?siguiente"));
        $smarty->assign('link_fin',completarURL(PAG_LIST_NOT_USU."?final"));

    	$smarty->assign('action',completarURL(PAG_LIST_NOT_USU));

	$smarty->display(TPL_LISTADO);

        $smarty->assign('linkPie',completarURL(PAG_AREA_PERSONAL));
 	$smarty->assign('textoLinkPie',ETIQ_LINK_AREA_PERSONAL);

	$smarty->display(TPL_PIE);

}

?>