<?php

require_once("../comunes/config.php");

// arrays que definen los campos que se van a mostrar en las cabeceras de la tablas y su correspondencia con el campo de bd que van a mostrar
$campos = array( array( "casilla" => "A",            "bd" => "activa"),
                 array( "casilla" => "Oferta",       "bd" => "oferta"),
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
$parametrosPermitidos = array ("empresa"   => "idEmpresa",
                               "orden"     => array("oferta", "fechaIni", "fechaFin", "activa"),
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
$parametrosConsulta = array("empresa" => "",
                            "orden"   => "oferta",
                            "sentido" => "asc");

//si damos de alta una subordenación, antes de la consulta completamos con el orden de los demás campos de la tabla en función del que se
//elige para ordenar
$subordenaciones = array ("oferta" => array ("fechaIni", "fechaFin", "activa"),
                          "fechaIni" => array ("oferta", "activa", "fechaFin"),
                          "fechaFin" => array ("oferta", "actica", "fechaIni"),
                          "activa" => array ("oferta", "fechaIni", "fechaFin")
                         );


////////////////// LOGICA DE PROCESO /////////////////////////////////////////////////////////////////////////////////////////////////////////

$nivel = chequearConexion();

//no se actualiza la variables de sesion "listados", porque desde este listado no se puede ir a ningún lada nada más que a limpiar el historial

if ($nivel >= PREMIUM) {  //sólo pueden acceder a los listados de accesos el administrador

         $parametros = chequearGET($_GET,$parametrosPermitidos);

         //tanto el gestor y el administrador tienen los mismo privilegios aquí, no tenemos que tener en cuenta el nivel

         $error="";
         if (chequearParametros($parametros, $parametrosConsulta, "paramConOfeEmp", $error)) {
             $cadOrdenacion = ordenar(SESSION("paramConOfeEmp"),$subordenaciones);
             $empresa = SESSION_CLAVE("paramConOfeEmp","empresa");
             if (!$_SESSION["ConOfeEmp"]=Consulta::obtenerConsultaOfertas($empresa,$cadOrdenacion,TAM_PAG_ACC)) $error=ETIQ_CONSULTA_FALLIDA;
             $parametros["inicio"]=""; //damos de alta el parámetro "inicio" para que se muestre la primera página si no hay error...
         } else if (!$error) SESSION("ConOfeEmp")->actualizarPag();

         if  (($nivel == PREMIUM) &&
              (SESSION_CLAVE("paramConOfeEmp","empresa")!= Empresa::obtenerPorIdUsuario(SESSION_CAMPO("usuario","idUsuario"))->obtenerCampo("idEmpresa")))
             $error=ETIQ_USUARIO_NO_AUTORIZADO;

         if ((!$error) AND (!SESSION("ConOfeEmp")->totReg())) $error=ETIQ_NO_HAY_REGISTROS;

         // si no hay errores, la peticion es correcta, hay privilegios y la consulta se ha establecido o ya se había creado... se muestran los datos...
         if (!$error) {

                         if (array_key_exists("inicio",$parametros))    listadoFormulario(SESSION("ConOfeEmp")->pagInicial(),$campos);
		     elseif (array_key_exists("pagina",$parametros))    listadoFormulario(SESSION("ConOfeEmp")->irPag($parametros["pagina"]),$campos);
		     elseif (POST("pagina"))                            listadoFormulario(SESSION("ConOfeEmp")->irPag(POST("pagina")),$campos); //la página se ha pedido por el formulario mostrado
		     elseif (array_key_exists("final",$parametros))     listadoFormulario(SESSION("ConOfeEmp")->pagFinal(),$campos);
                     elseif (array_key_exists("anterior",$parametros))  listadoFormulario(SESSION("ConOfeEmp")->pagAnterior(),$campos);
		     elseif (array_key_exists("siguiente",$parametros)) listadoFormulario(SESSION("ConOfeEmp")->pagSiguiente(),$campos);
                       else                                             listadoFormulario(SESSION("ConOfeEmp")->actualizarPag(),$campos); //si no se indicó ningún parámetro de pagina, anterior, etc...
                                                                                                                                      //por lo que si había consulta ya, se refresca la página nada más

        } else { //y aquí se muestran los errores que se han detectado

              if ($error == ETIQ_USUARIO_NO_AUTORIZADO) informacion($error,AREA_RESTRINGIDA,"",PAG_AREA_PERSONAL,ETIQ_LINK_AREA_PERSONAL);
              elseif ($error == ETIQ_NO_HAY_REGISTROS) informacion($error,INFORMACION,"",
                                PAG_EMPRESAS."?usuario=".(Empresa::obtenerPorIdEmpresa(SESSION_CLAVE("paramConOfeEmp","empresa"))->obtenerCampo("idUsuario")),
                                ETIQ_LINK_VOLVER,PAG_AREA_PERSONAL,ETIQ_LINK_AREA_PERSONAL);
              else informacion($error,ERROR,"",PAG_AREA_PERSONAL,ETIQ_LINK_AREA_PERSONAL);
        }

} else { //si no tiene suficiente nivel, le damos mensaje de error en función del nivel del usuario

         if ($nivel==ANONIMO) informacion(ETIQ_ACCESO_RESERVADO,AREA_RESTRINGIDA,"",PAG_REG_PPAL,ETIQ_LINK_REG,PAG_CONEX,ETIQ_BT_CONECTAR);
         else informacion(ETIQ_USUARIO_NO_AUTORIZADO,AREA_RESTRINGIDA,"",PAG_AREA_PERSONAL,ETIQ_LINK_AREA_PERSONAL);
}

/////////////////// FUNCIONES /////////////////////////////////////////////////////////////////////////////////////////////


function listadoFormulario($datos, $campos) {

        $smarty = nuevaPlantilla();
        $empresa = Empresa::obtenerPorIdEmpresa(SESSION_CLAVE("paramConOfeEmp","empresa"));
        $nombreEmpresa = $empresa->obtenerCampo("empresa");
        $smarty->assign('tituloPagina',ETIQ_TIT_LIST_OFEEMP.'"'.$nombreEmpresa.'"');

        $mensajes=array();
        $mensajes[]="Ofertas de la ".SESSION("ConOfeEmp")->posIniPag().
                    " a la ".SESSION("ConOfeEmp")->posFinPag().
		    " (de ".SESSION("ConOfeEmp")->totReg().")";
	$smarty->assign('mensajes',$mensajes);

        $smarty->display(TPL_CABECERA);

    	$smarty->assign('nombreTabla',"tablaOfertasEmpresa");
    	$smarty->assign('numFilas',count($datos));
    	$smarty->assign('numColumnas',count($campos));

        for($c=0; $c<count($campos); $c++) {
            $sentido="asc";
            if (SESSION_CLAVE("paramConOfeEmp","orden") == $campos[$c]["bd"]) {
                $sentido = (SESSION_CLAVE("paramConOfeEmp","sentido") == "asc") ? "desc" : "asc";
                if ($sentido=="asc")
                    $smarty->assign('class_link_th'.$c,'class="ordenDesc"');
                else
                    $smarty->assign('class_link_th'.$c,'class="ordenAsc"');
            }
       	    $smarty->assign('link_th'.$c,completarURL(PAG_LIST_OFE_EMP."?orden=".$campos[$c]["bd"]."&sentido=".$sentido));
       	    if ((SESSION_CLAVE("paramConOfeEmp","orden")=="activa")&&($campos[$c]["casilla"]=="A")) $smarty->assign('dato_th'.$c,"");
       	    else $smarty->assign('dato_th'.$c,$campos[$c]["casilla"]);
       	}
        for($f=0; $f<count($datos); $f++) {
            for($c=0; $c<count($campos); $c++) {
                if ($campos[$c]["bd"] == "oferta") {
                    $smarty->assign('link_td'.$f.$c,'<a href="'.completarURL(PAG_OFERTAS."?oferta=".$datos[$f]["idOferta"]).'">');
                }
                if ($campos[$c]["bd"] == "activa") {
                       $d = ($datos[$f][$campos[$c]["bd"]]) ? "&nbsp;" : "D";
                       $smarty->assign('dato_td'.$f.$c,$d);
                } else $smarty->assign('dato_td'.$f.$c,$datos[$f][$campos[$c]["bd"]]);
       	    }
        }

      	$smarty->assign('pagAct',SESSION("ConOfeEmp")->pagAct());
 	$smarty->assign('totPag',SESSION("ConOfeEmp")->totPag());
	$smarty->assign('totReg',SESSION("ConOfeEmp")->totReg());

        $smarty->assign('link_ini',completarURL(PAG_LIST_OFE_EMP."?inicio"));
        $smarty->assign('link_ant',completarURL(PAG_LIST_OFE_EMP."?anterior"));
        $smarty->assign('link_sgt',completarURL(PAG_LIST_OFE_EMP."?siguiente"));
        $smarty->assign('link_fin',completarURL(PAG_LIST_OFE_EMP."?final"));

    	$smarty->assign('action',completarURL(PAG_LIST_OFE_EMP));

	$smarty->display(TPL_LISTADO);


        $smarty->assign('linkAlt',completarURL(PAG_EMPRESAS."?usuario=".$empresa->obtenerCampo("idUsuario"),ETIQ_LINK_VOLVER));
 	$smarty->assign('textoLinkAlt',ETIQ_LINK_VOLVER_EMPRESA);
        $smarty->assign('linkPie',completarURL(PAG_AREA_PERSONAL));
 	$smarty->assign('textoLinkPie',ETIQ_LINK_AREA_PERSONAL);

	$smarty->display(TPL_PIE);

}

?>








