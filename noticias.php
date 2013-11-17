<?php

require_once("comunes/config.php");

// arrays que definen los campos que se van a mostrar en las cabeceras de la tablas y su correspondencia con el campo de bd que van a mostrar
$campos = array( array( "campo" => "titular", "bd" => "titular"),
                 array( "campo" => "fecha",   "bd" => "fechaNot"),
                 array( "campo" => "resumen", "bd" => "resumen"),
                 array( "campo" => "texto",   "bd" => "textoNot"),
                 array( "campo" => "link",    "bd" => "linkRef"),
);

//se definen los parametros que se permiten por GET (primera clave) y por cada uno un aray con una clave "valores"
//si "valores" es un array, es la enumeraci�n de los valores permitidos
//si "valores" es una cadena, puede ser vac�a, lo que indica que NO se pide valor, solo que indique el par�metro
//y en otro caso la cadena de "valores" debe ser una expresi�n regular con la que se intentar� cazar el valor del par�metro de entrada.
//en "omision" se indicar� el valor que se toma por defecto si se indica el par�metro pero su valor no se especifica o es erroneo
//cuando "valores" es un array, normalemente un valor del array o la cadena vac�a
//si "valores" es una expresi�n regular, un valor que casa con ella, por ejemplo un n�mero entero por defecto
//si "valores" es la cadena vac�a da igual "omisi�n", s�lo se espera de ese par�metro la cadena vac�a.
$parametrosPermitidos = array ("noticia"   => "idNoticia",
                               "numreg"    => "tamPag",
                               "pagina"    => "numVisitas",  //se le impone como condicion un n�mero como por ejemplo el numVistas de la tabla Accesos;
                               "inicio"    => "",
                               "final"     => "",
                               "siguiente" => "",
                               "anterior"  => ""
                 );


////////////////// LOGICA DE PROCESO /////////////////////////////////////////////////////////////////////////////////////////////////////////

         $nivel = chequearConexion(); //para seguir con la sesi�n, no hay restricci�n de usuarios a esta p�gina

         $parametros = chequearGET($_GET,$parametrosPermitidos);

         if (array_key_exists("noticia",$parametros)) {
              if ($noticia=Noticia::obtenerPorIdNoticia($parametros["noticia"]))
                  if (!$noticia->obtenerCampo("activa")) $noticia = "";
         } else $noticia = "";

         $tamPagxDefecto = ($noticia) ? TAM_PAG_NOT_PPAL_RED : TAM_PAG_NOT_PPAL_NOR;

             if (array_key_exists("numreg",$parametros)) $tamPag=$parametros["numreg"];
         elseif (POST("numreg")) $tamPag=POST("numreg");
           else $tamPag = 0;

         $error="";
         if ((!SESSION("noticias"))||((!POST("numreg"))&&(!POST("pagina"))&&(!$parametros))) {
             if (!$_SESSION["noticias"]=Consulta::obtenerNoticias()) $error=ETIQ_CONSULTA_FALLIDA;
             if (!$error) {
                 $tamPag = ($tamPag) ? $tamPag : $tamPagxDefecto;
                 $_SESSION["noticias"]->modTamPag($tamPag);
             }
             $parametros["inicio"]=""; //damos de alta el par�metro "inicio" para que se muestre la primera p�gina si no hay error...
         } elseif ($tamPag) SESSION("noticias")->modTamPag($tamPag);
             else  SESSION("noticias")->actualizarPag();

         // si no hay errores, la peticion es correcta, hay privilegios y la consulta se ha establecido o ya se hab�a creado... se muestran los datos...
         if (!$error) {
                         //los par�metros de movimiento de p�gina tienen este orden de precedencia, inicio el primero xq cuando hay nueva consulta, lo activamos para que se inicie ah�
       		         if (array_key_exists("inicio",$parametros))    listadoNoticias($nivel, SESSION("noticias")->pagInicial(),$campos, $noticia);
		     elseif (array_key_exists("pagina",$parametros))    listadoNoticias($nivel, SESSION("noticias")->irPag($parametros["pagina"]),$campos,  $noticia);
		     elseif (POST("pagina"))                            listadoNoticias($nivel, SESSION("noticias")->irPag(POST("pagina")),$campos,  $noticia);
		     elseif (array_key_exists("final",$parametros))     listadoNoticias($nivel, SESSION("noticias")->pagFinal(),$campos, $noticia);
                     elseif (array_key_exists("anterior",$parametros))  listadoNoticias($nivel, SESSION("noticias")->pagAnterior(),$campos, $noticia);
		     elseif (array_key_exists("siguiente",$parametros)) listadoNoticias($nivel, SESSION("noticias")->pagSiguiente(),$campos, $noticia);
                       else                                             listadoNoticias($nivel, SESSION("noticias")->actualizarPag(),$campos, $noticia); //si no se indic� ning�n par�metro de pagina, anterior, etc...
                                                                                                                                      //por lo que si hab�a consulta ya, se refresca la p�gina nada m�s
        } else { //y aqu� se muestran los errores que se han detectado

              informacion($error,INFORMACION,"",PAG_PPAL,ETIQ_LINK_PAG_PPAL);
        }



/////////////////// FUNCIONES /////////////////////////////////////////////////////////////////////////////////////////////

function listadoNoticias($nivel, $datos, $campos, $noticia) {

        cabeceraPrincipal($nivel);

        $smarty = nuevaPlantilla();

        if ($noticia) {
               $idNoticia = $noticia->obtenerCampo("idNoticia");
               $smarty->assign('noticia',$idNoticia);
               for($c=0; $c<count($campos); $c++)
                   $smarty->assign($campos[$c]["campo"],$noticia->obtenerCampo($campos[$c]["bd"]));
               $smarty->assign('tipoNoticia',"completa");
               $smarty->display(TPL_VISTA_NOT);
               $YNoticia = "&amp;noticia=".$idNoticia;
               $masNoticia = "?noticia=".$idNoticia;
         } else {
               if (isset($_GET["noticia"])) {
                   $smarty->assign('mensajeCentral',ETIQ_NOTICIA_NO_ENCONTRADA);
                   $smarty->assign('class_mensajeCentral','class="errorNotPpal"');
                   $smarty->display(TPL_MENSAJE);
               }
               $YNoticia = "";
               $masNoticia = "";
        }

        $numRegistros = count($datos);

        if ($numRegistros > 0) {
             $smarty->assign('tipoNoticia',"breve");
             for($r=0; $r<$numRegistros; $r++) {
                 for($c=0; $c<count($campos); $c++)
                     $smarty->assign($campos[$c]["campo"],$datos[$r][$campos[$c]["bd"]]);
                 $smarty->assign('link_titular',completarURL(PAG_PPAL_NOTICIAS."?noticia=".$datos[$r]['idNoticia']));
                 $smarty->display(TPL_VISTA_NOT);
             }
        } else {
               $smarty->assign('mensajeCentral',ETIQ_NO_HAY_NOTICIAS);
               $smarty->assign('class_mensajeCentral','class="errorEmpPPal"');
               $smarty->display(TPL_MENSAJE);
               $smarty->assign('mostrar_texto',false);
        }

      	$smarty->assign('pagAct',SESSION("noticias")->pagAct());
 	$smarty->assign('totPag',SESSION("noticias")->totPag());
	$smarty->assign('totReg',SESSION("noticias")->totReg());
	$smarty->assign('tamPag',SESSION("noticias")->obtenerCampo("tamPag"));

        $smarty->assign('link_ini',completarURL(PAG_PPAL_NOTICIAS."?inicio$YNoticia"));
        $smarty->assign('link_ant',completarURL(PAG_PPAL_NOTICIAS."?anterior$YNoticia"));
        $smarty->assign('link_sgt',completarURL(PAG_PPAL_NOTICIAS."?siguiente$YNoticia"));
        $smarty->assign('link_fin',completarURL(PAG_PPAL_NOTICIAS."?final$YNoticia"));
        
 	$smarty->assign('action',completarURL(PAG_PPAL_NOTICIAS.$masNoticia));

	$smarty->display(TPL_SUBPIE_PPAL);

        $smarty->assign('normativa',completarURL(PAG_PPAL_NORMATIVA));
	$smarty->display(TPL_PIE_PPAL);

}
?>