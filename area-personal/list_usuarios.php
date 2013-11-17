<?php

require_once("../comunes/config.php");

// arrays que definen los campos que se van a mostrar en las cabeceras de la tablas y su correspondencia con el campo de bd que van a mostrar
$campos = array( array( "casilla" => "Tipo",             "bd" => "tipoUsuario"), //no quiero mostrar nombre en la cabecera porque va a ser un s�lo caracter
                 array( "casilla" => "Usuario",          "bd" => "usuario"),
                 array( "casilla" => "Nombre",           "bd" => "nombre"),
                 array( "casilla" => "Primer Apellido",  "bd" => "apellido1"),
                 array( "casilla" => "Segundo Apellido", "bd" => "apellido2")
);

//se definen los parametros que se permiten por GET (primera clave) y por cada uno un aray con una clave "valores"
//si "valores" es un array, es la enumeraci�n de los valores permitidos
//si "valores" es una cadena, puede ser vac�a, lo que indica que NO se pide valor, solo que indique el par�metro
//y en otro caso la cadena de "valores" debe ser una expresi�n regular con la que se intentar� cazar el valor del par�metro de entrada.
//en "omision" se indicar� el valor que se toma por defecto si se indica el par�metro pero su valor no se especifica o es erroneo
//cuando "valores" es un array, normalemente un valor del array o la cadena vac�a
//si "valores" es una expresi�n regular, un valor que casa con ella, por ejemplo un n�mero entero por defecto
//si "valores" es la cadena vac�a da igual "omisi�n", s�lo se espera de ese par�metro la cadena vac�a.
$parametrosPermitidos = array ("orden"     => array("nombre", "usuario", "apellido1", "apellido2", "tipoUsuario"),
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

$parametrosConsulta = array("orden"   => "usuario",
                            "sentido" => "asc");

//si damos de alta una subordenaci�n, antes de la consulta completamos con el orden de los dem�s campos de la tabla en funci�n del que se
//elige para ordenar
$subordenaciones = array ("usuario" => array ("nombre", "apellido1", "apellido2"),
                          "nombre" => array ("apellido1", "apellido2", "usuario"),
                          "apellido1" => array ("apellido2", "nombre", "usuario"),
                          "apellido2" => array ("apellido1", "nombre", "usuario"),
                          "tipoUsuario" => array ("nombre", "apellido1", "apellido2", "usuario")
                         );



////////////////// LOGICA DE PROCESO /////////////////////////////////////////////////////////////////////////////////////////////////////////

$nivel = chequearConexion();


if (($nivel == GESTOR)OR($nivel == ADMON)) {  //s�lo pueden acceder a listados los gestores y el administradore

         $parametros = chequearGET($_GET,$parametrosPermitidos);

         //tanto el gestor y el administrador tienen los mismo privilegios aqu�, no tenemos que tener en cuenta el nivel

         $error="";
         if (chequearParametros($parametros, $parametrosConsulta, "paramConUsu", $error)) {
             $cadOrdenacion = ordenar(SESSION("paramConUsu"),$subordenaciones);
             if (!$_SESSION["ConUsu"]=Consulta::obtenerConsultaUsuarios($cadOrdenacion,TAM_PAG_USU)) $error=ETIQ_CONSULTA_FALLIDA;
             $parametros["inicio"]=""; //damos de alta el par�metro "inicio" para que se muestre la primera p�gina si no hay error...
         } else if (!$error) SESSION("ConUsu")->actualizarPag();

         if ((!$error) AND(!SESSION("ConUsu")->totReg())) $error=ETIQ_NO_HAY_REGISTROS;

         // si no hay errores, la peticion es correcta, hay privilegios y la consulta se ha establecido o ya se hab�a creado... se muestran los datos...
         if (!$error) {

                         //los par�metros de movimiento de p�gina tienen este orden de precedencia, inicio el primero xq cuando hay nueva consulta, lo activamos para que se inicie ah�
       		         if (array_key_exists("inicio",$parametros))    listadoFormulario(SESSION("ConUsu")->pagInicial(),$campos);
		     elseif (array_key_exists("pagina",$parametros))    listadoFormulario(SESSION("ConUsu")->irPag($parametros["pagina"]),$campos);
		     elseif (POST("pagina"))                            listadoFormulario(SESSION("ConUsu")->irPag(POST("pagina")),$campos); //la p�gina se ha pedido por el formulario mostrado
		     elseif (array_key_exists("final",$parametros))     listadoFormulario(SESSION("ConUsu")->pagFinal(),$campos);
                     elseif (array_key_exists("anterior",$parametros))  listadoFormulario(SESSION("ConUsu")->pagAnterior(),$campos);
		     elseif (array_key_exists("siguiente",$parametros)) listadoFormulario(SESSION("ConUsu")->pagSiguiente(),$campos);
                       else                                             listadoFormulario(SESSION("ConUsu")->actualizarPag(),$campos); //si no se indic� ning�n par�metro de pagina, anterior, etc...
                                                                                                                                      //por lo que si hab�a consulta ya, se refresca la p�gina nada m�s
        } else { //y aqu� se muestran los errores que se han detectado

              informacion($error,INFORMACION,"",PAG_AREA_PERSONAL,ETIQ_LINK_AREA_PERSONAL);
         }

} else { //si no tiene suficiente nivel, le damos mensaje de error en funci�n del nivel del usuario

         if ($nivel==ANONIMO) informacion(ETIQ_ACCESO_RESERVADO,AREA_RESTRINGIDA,"",PAG_REG_PPAL,ETIQ_LINK_REG,PAG_CONEX,ETIQ_BT_CONECTAR);
         else informacion(ETIQ_USUARIO_NO_AUTORIZADO,AREA_RESTRINGIDA,"",PAG_AREA_PERSONAL,ETIQ_LINK_AREA_PERSONAL);

}

/////////////////// FUNCIONES /////////////////////////////////////////////////////////////////////////////////////////////


function listadoFormulario($datos, $campos) {

        $smarty = nuevaPlantilla();

        $smarty->assign('tituloPagina',ETIQ_TIT_LIST_USU);

        $mensajes=array();
        $mensajes[]="Usuarios del ".SESSION("ConUsu")->posIniPag().
                    " al ".SESSION("ConUsu")->posFinPag().
		    " (de ".SESSION("ConUsu")->totReg().")";
	$smarty->assign('mensajes',$mensajes);

        $smarty->display(TPL_CABECERA);
        
    	$smarty->assign('nombreTabla',"tablaUsuarios");
    	$smarty->assign('numFilas',count($datos));
    	$smarty->assign('numColumnas',count($campos));

        for($c=0; $c<count($campos); $c++) {
            $sentido="asc";
            if (SESSION_CLAVE("paramConUsu","orden") == $campos[$c]["bd"]) {
                $sentido = (SESSION_CLAVE("paramConUsu","sentido") == "asc") ? "desc" : "asc";
                $smarty->assign('class_link_th'.$c,'class="orden"');
                if ($sentido=="asc")
                    $smarty->assign('class_link_th'.$c,'class="ordenDesc"');
                else
                    $smarty->assign('class_link_th'.$c,'class="ordenAsc"');
            }
       	    $smarty->assign('link_th'.$c,completarURL(PAG_LIST_USU."?orden=".$campos[$c]["bd"]."&sentido=".$sentido));
       	    $smarty->assign('dato_th'.$c,$campos[$c]["casilla"]);
       	}
        for($f=0; $f<count($datos); $f++) {
            for($c=0; $c<count($campos); $c++) {
                if ($campos[$c]["bd"] == "usuario") {
                    $smarty->assign('link_td'.$f.$c,'<a href="'.completarURL(PAG_USUARIOS."?usuario=".$datos[$f]["idUsuario"]).'">');
                }
                if ($campos[$c]["bd"] == "tipoUsuario")
       	           $smarty->assign('dato_td'.$f.$c,strtoupper($datos[$f][$campos[$c]["bd"]][0]));
                else
       	           $smarty->assign('dato_td'.$f.$c,$datos[$f][$campos[$c]["bd"]]);
       	    }
        }

      	$smarty->assign('pagAct',SESSION("ConUsu")->pagAct());
 	$smarty->assign('totPag',SESSION("ConUsu")->totPag());
	$smarty->assign('totReg',SESSION("ConUsu")->totReg());

        $smarty->assign('link_ini',completarURL(PAG_LIST_USU."?inicio"));
        $smarty->assign('link_ant',completarURL(PAG_LIST_USU."?anterior"));
        $smarty->assign('link_sgt',completarURL(PAG_LIST_USU."?siguiente"));
        $smarty->assign('link_fin',completarURL(PAG_LIST_USU."?final"));

    	$smarty->assign('action',completarURL(PAG_LIST_USU));

	$smarty->display(TPL_LISTADO);

        $smarty->assign('linkPie',completarURL(PAG_AREA_PERSONAL));
 	$smarty->assign('textoLinkPie',ETIQ_LINK_AREA_PERSONAL);

	$smarty->display(TPL_PIE);

}

?>








