<?php

require_once("../comunes/config.php");

// arrays que definen los campos que se van a mostrar en las cabeceras de la tablas y su correspondencia con el campo de bd que van a mostrar
$campos = array( array( "casilla" => "A",            "bd" => "activa"),
                 array( "casilla" => "Oferta",       "bd" => "oferta"),
                 array( "casilla" => "Empresa",      "bd" => "empresa"),
                 array( "casilla" => "Fecha Inicio", "bd" => "fechaIni"),
                 array( "casilla" => "Fecha Fin",    "bd" => "fechaFin")
);

//se definen los parametros que se permiten por GET (primera clave) y por cada uno un aray con una clave "valores"
//si "valores" es un array, es la enumeración de los valores permitidos
//si "valores" es una cadena, puede ser vacía, lo que indica que NO se pide valor, solo que indique el parámetro
//y en otro caso la cadena de "valores" debe ser una expresión regular con la que se intentará cazar el valor del parámetro de entrada.
//en "omision" se indicará el valor que se toma por defecto si se indica el parámetro pero su valor no se especifica o es erroneo
//cuando "valores" es un array, normalemente un valor del array o la cadena vacía
//si "valores" es una expresión regular, un valor que casa con ella, por ejemplo un número entero por defecto
//si "valores" es la cadena vacía da igual "omisión", sólo se espera de ese parámetro la cadena vacía.
$parametrosPermitidos = array ("orden"     => array("oferta", "empresa", "fechaIni", "fechaFin", "activa"),
                               "sentido"   => array("asc", "desc"),
                               "pagina"    => "numVisitas",  //se le impone como condicion un número como por ejemplo el numVistas de la tabla Accesos;
                               "inicio"    => "",
                               "final"     => "",
                               "siguiente" => "",
                               "anterior"  => ""
                 );

//en este array definimos los campos que determinan un cambio en la consulta (nueva llamada a la BD)
//el primer campo es una clave, subgrupo del anterior array
//el segundo el valor por omisión en caso que no esté especificado, la cadena vacía si es obligatorio especificarlo
//por lo menos la primera vez que se entra, ya que si no, dará un error de mal llamada.

$parametrosConsulta = array("orden"   => "oferta",
                            "sentido" => "asc");

//si damos de alta una subordenación, antes de la consulta completamos con el orden de los demás campos de la tabla en función del que se
//elige para ordenar
$subordenaciones = array ("oferta" => array ("empresa", "fechaIni", "fechaFin"),
                          "empresa" => array ("oferta", "fechaIni", "fechaFin"),
                          "fechaIni" => array ("oferta", "fechaFin", "empresa"),
                          "fechaFin" => array ("oferta", "fechaIni", "empresa"),
                          "activa" => array ("oferta", "fechaIni", "fechaFin", "empresa")
                         );



////////////////// LOGICA DE PROCESO /////////////////////////////////////////////////////////////////////////////////////////////////////////

$nivel = chequearConexion();

if (($nivel == GESTOR)OR($nivel == ADMON)) {  //sólo pueden acceder a los listados de accesos el administrador

         $parametros = chequearGET($_GET,$parametrosPermitidos);

         //tanto el gestor y el administrador tienen los mismo privilegios aquí, no tenemos que tener en cuenta el nivel

         $error="";
         if (chequearParametros($parametros, $parametrosConsulta, "paramConOfe", $error)) {
             $cadOrdenacion = ordenar(SESSION("paramConOfe"),$subordenaciones);
             if (!$_SESSION["ConOfe"]=Consulta::obtenerConsultaOfertas("",$cadOrdenacion,TAM_PAG_ACC)) $error=ETIQ_CONSULTA_FALLIDA;
             $parametros["inicio"]=""; //damos de alta el parámetro "inicio" para que se muestre la primera página si no hay error...
         } else if (!$error) SESSION("ConOfe")->actualizarPag();

         if ((!$error) AND (!SESSION("ConOfe")->totReg())) $error=ETIQ_NO_HAY_REGISTROS;

         // si no hay errores, la peticion es correcta, hay privilegios y la consulta se ha establecido o ya se había creado... se muestran los datos...
         if (!$error) {

                         //los parámetros de movimiento de página tienen este orden de precedencia, inicio el primero xq cuando hay nueva consulta, lo activamos para que se inicie ahí
       		         if (array_key_exists("inicio",$parametros))    listadoFormulario(SESSION("ConOfe")->pagInicial(),$campos);
		     elseif (array_key_exists("pagina",$parametros))    listadoFormulario(SESSION("ConOfe")->irPag($parametros["pagina"]),$campos);
		     elseif (POST("pagina"))                            listadoFormulario(SESSION("ConOfe")->irPag(POST("pagina")),$campos); //la página se ha pedido por el formulario mostrado
		     elseif (array_key_exists("final",$parametros))     listadoFormulario(SESSION("ConOfe")->pagFinal(),$campos);
                     elseif (array_key_exists("anterior",$parametros))  listadoFormulario(SESSION("ConOfe")->pagAnterior(),$campos);
		     elseif (array_key_exists("siguiente",$parametros)) listadoFormulario(SESSION("ConOfe")->pagSiguiente(),$campos);
                       else                                             listadoFormulario(SESSION("ConOfe")->actualizarPag(),$campos); //si no se indicó ningún parámetro de pagina, anterior, etc...
                                                                                                                                      //por lo que si había consulta ya, se refresca la página nada más
        } else { //y aquí se muestran los errores que se han detectado

              informacion($error,INFORMACION,"",PAG_AREA_PERSONAL,ETIQ_LINK_AREA_PERSONAL);
         }

} else { //si no tiene suficiente nivel, le damos mensaje de error en función del nivel del usuario

         if ($nivel==ANONIMO) informacion(ETIQ_ACCESO_RESERVADO,AREA_RESTRINGIDA,"",PAG_REG_PPAL,ETIQ_LINK_REG,PAG_CONEX,ETIQ_BT_CONECTAR);
         else informacion(ETIQ_USUARIO_NO_AUTORIZADO,AREA_RESTRINGIDA,"",PAG_AREA_PERSONAL,ETIQ_LINK_AREA_PERSONAL);

}

/////////////////// FUNCIONES /////////////////////////////////////////////////////////////////////////////////////////////


function listadoFormulario($datos, $campos) {

        $smarty = nuevaPlantilla();

        $smarty->assign('tituloPagina',ETIQ_TIT_LIST_OFE);

        $mensajes=array();
        $mensajes[]="Ofertas de la ".SESSION("ConOfe")->posIniPag().
                    " a la ".SESSION("ConOfe")->posFinPag().
		    " (de ".SESSION("ConOfe")->totReg().")";
	$smarty->assign('mensajes',$mensajes);

        $smarty->display(TPL_CABECERA);

    	$smarty->assign('nombreTabla',"tablaOfertasGral");
    	$smarty->assign('numFilas',count($datos));
    	$smarty->assign('numColumnas',count($campos));

        for($c=0; $c<count($campos); $c++) {
            $sentido="asc";
            if (SESSION_CLAVE("paramConOfe","orden") == $campos[$c]["bd"]) {
                $sentido = (SESSION_CLAVE("paramConOfe","sentido") == "asc") ? "desc" : "asc";
                if ($sentido=="asc")
                    $smarty->assign('class_link_th'.$c,'class="ordenDesc"');
                else
                    $smarty->assign('class_link_th'.$c,'class="ordenAsc"');
            }
       	    $smarty->assign('link_th'.$c,completarURL(PAG_LIST_OFE."?orden=".$campos[$c]["bd"]."&sentido=".$sentido));
       	    if ((SESSION_CLAVE("paramConOfe","orden")=="activa")&&($campos[$c]["casilla"]=="A")) $smarty->assign('dato_th'.$c,"");
       	    else $smarty->assign('dato_th'.$c,$campos[$c]["casilla"]);
       	}
        for($f=0; $f<count($datos); $f++) {
            for($c=0; $c<count($campos); $c++) {
                if ($campos[$c]["bd"] == "oferta") {
                    $smarty->assign('link_td'.$f.$c,'<a href="'.completarURL(PAG_OFERTAS."?oferta=".$datos[$f]["idOferta"]).'">');
                }
                if ($campos[$c]["bd"] == "empresa") {
                    $smarty->assign('link_td'.$f.$c,'<a href="'.completarURL(PAG_EMPRESAS."?usuario=".$datos[$f]["idUsuario"]).'">');
                }
                if ($campos[$c]["bd"] == "activa") {
                       $d = ($datos[$f][$campos[$c]["bd"]]) ? "&nbsp;" : "D";
                       $smarty->assign('dato_td'.$f.$c,$d);
                } else $smarty->assign('dato_td'.$f.$c,$datos[$f][$campos[$c]["bd"]]);
       	    }
        }

      	$smarty->assign('pagAct',SESSION("ConOfe")->pagAct());
 	$smarty->assign('totPag',SESSION("ConOfe")->totPag());
	$smarty->assign('totReg',SESSION("ConOfe")->totReg());

        $smarty->assign('link_ini',completarURL(PAG_LIST_OFE."?inicio"));
        $smarty->assign('link_ant',completarURL(PAG_LIST_OFE."?anterior"));
        $smarty->assign('link_sgt',completarURL(PAG_LIST_OFE."?siguiente"));
        $smarty->assign('link_fin',completarURL(PAG_LIST_OFE."?final"));

    	$smarty->assign('action',completarURL(PAG_LIST_OFE));

	$smarty->display(TPL_LISTADO);

        $smarty->assign('linkPie',completarURL(PAG_AREA_PERSONAL));
 	$smarty->assign('textoLinkPie',ETIQ_LINK_AREA_PERSONAL);

	$smarty->display(TPL_PIE);

}

?>