<?php

require_once("comunes/config.php");

// arrays que definen los campos que se van a mostrar en las cabeceras de la tablas y su correspondencia con el campo de bd que van a mostrar
$campos = array( array( "campo" => "oferta",      "bd" => "oferta"),
                 array( "campo" => "texto",       "bd" => "textoOferta"),
                 array( "campo" => "condiciones", "bd" => "textoCond"),
                 array( "campo" => "fecha",       "bd" => "fechaFin")
);


//se definen los parametros que se permiten por GET (primera clave) y por cada uno un aray con una clave "valores"
//si "valores" es un array, es la enumeración de los valores permitidos
//si "valores" es una cadena, puede ser vacía, lo que indica que NO se pide valor, solo que indique el parámetro
//y en otro caso la cadena de "valores" debe ser una expresión regular con la que se intentará cazar el valor del parámetro de entrada.
//en "omision" se indicará el valor que se toma por defecto si se indica el parámetro pero su valor no se especifica o es erroneo
//cuando "valores" es un array, normalemente un valor del array o la cadena vacía
//si "valores" es una expresión regular, un valor que casa con ella, por ejemplo un número entero por defecto
//si "valores" es la cadena vacía da igual "omisión", sólo se espera de ese parámetro la cadena vacía.
$parametrosPermitidos = array ("oferta"    => "idOferta",
                               "empresa"   => "idEmpresa",
                               "sector"    => "idSector",
                               "poblacion" => "idPoblacion",
                               "numreg"    => "tamPag",
                               "pagina"    => "numVisitas",  //se le impone como condicion un número como por ejemplo el numVistas de la tabla Accesos;
                               "inicio"    => "",
                               "final"     => "",
                               "siguiente" => "",
                               "anterior"  => ""
                 );


////////////////// LOGICA DE PROCESO /////////////////////////////////////////////////////////////////////////////////////////////////////////

         $nivel = chequearConexion(); //para seguir con la sesión, no hay restricción de usuarios a esta página

         $parametros = chequearGET($_GET,$parametrosPermitidos);

             if (array_key_exists("oferta",$parametros)) $oferta=$parametros["oferta"];
           else $oferta = 0;

             if (array_key_exists("empresa",$parametros)) $empresa=$parametros["empresa"];
         elseif (GET("empresa")) $empresa=GET("empresa");
           else $empresa = 0;

             if (($empresa == SESSION("empresaO"))) $empresa = 0;

             if (array_key_exists("sector",$parametros)) $sector=$parametros["sector"];
         elseif (POST("sector")) $sector=POST("sector");
           else $sector = ((!POST("buscar")AND(SESSION("sectorO")))) ? SESSION("sectorO"):0;

             if (array_key_exists("poblacion",$parametros)) $poblacion=$parametros["poblacion"];
         elseif (POST("poblacion")) $poblacion=POST("poblacion");
           else $poblacion = ((!POST("buscar")AND (SESSION("poblacionO")))) ? SESSION("poblacionO"):0;

            if (array_key_exists("numreg",$parametros)) $tamPag=$parametros["numreg"];
        elseif (POST("numreg")) $tamPag=POST("numreg");
          else $tamPag = 0;

         $error="";
         if (!SESSION("ofertas")||(POST("buscar"))||($empresa)||($oferta)||
              ((!POST("buscar"))&&(!POST("numreg"))&&(!POST("pagina"))&&(!$parametros))) {
             if ((!POST("buscar"))&&(!POST("numreg"))&&(!POST("pagina"))&&(!$parametros)) { $sector=0; $poblacion=0; }
             if (!$_SESSION["ofertas"]=Consulta::obtenerOfertas($empresa,$sector,$poblacion)) $error=ETIQ_CONSULTA_FALLIDA;
             if (!$error) {
                 $_SESSION["empresaO"]=$empresa;
                 $_SESSION["sectorO"]=$sector;
                 $_SESSION["poblacionO"]=$poblacion;
                 $tamPag = ($tamPag) ? $tamPag : TAM_PAG_OFE_PPAL;
                 $_SESSION["ofertas"]->modTamPag($tamPag);
             }
             $parametros["inicio"]=""; //damos de alta el parámetro "inicio" para que se muestre la primera página si no hay error...
         } elseif ($tamPag) SESSION("ofertas")->modTamPag($tamPag);
             else  SESSION("ofertas")->actualizarPag();

         // si no hay errores, la peticion es correcta, hay privilegios y la consulta se ha establecido o ya se había creado... se muestran los datos...
         if (!$error) {
                         //los parámetros de movimiento de página tienen este orden de precedencia, inicio el primero xq cuando hay nueva consulta, lo activamos para que se inicie ahí
       		         if (array_key_exists("inicio",$parametros))    listadoOfertas($nivel, SESSION("ofertas")->pagInicial(),$campos, $oferta, $sector, $poblacion);
		     elseif (array_key_exists("pagina",$parametros))    listadoOfertas($nivel, SESSION("ofertas")->irPag($parametros["pagina"]),$campos,  $oferta, $sector, $poblacion);
		     elseif (POST("pagina"))                            listadoOfertas($nivel, SESSION("ofertas")->irPag(POST("pagina")),$campos,  $oferta, $sector, $poblacion);
		     elseif (array_key_exists("final",$parametros))     listadoOfertas($nivel, SESSION("ofertas")->pagFinal(),$campos, $oferta, $sector, $poblacion);
                     elseif (array_key_exists("anterior",$parametros))  listadoOfertas($nivel, SESSION("ofertas")->pagAnterior(),$campos, $oferta, $sector, $poblacion);
		     elseif (array_key_exists("siguiente",$parametros)) listadoOfertas($nivel, SESSION("ofertas")->pagSiguiente(),$campos, $oferta, $sector, $poblacion);
                       else                                             listadoOfertas($nivel, SESSION("ofertas")->actualizarPag(),$campos, $oferta, $sector, $poblacion); //si no se indicó ningún parámetro de pagina, anterior, etc...
                                                                                                                                      //por lo que si había consulta ya, se refresca la página nada más
        } else { //y aquí se muestran los errores que se han detectado

              informacion($error,INFORMACION,"",PAG_PPAL,ETIQ_LINK_PAG_PPAL);
        }



/////////////////// FUNCIONES /////////////////////////////////////////////////////////////////////////////////////////////

function listadoOfertas($nivel, $datos, $campos, $idOferta, $sector, $poblacion) {

        cabeceraPrincipal($nivel);

        $smarty = nuevaPlantilla();

        $etiqBuscador = ETIQ_BUSCADOR_OFERTAS1;
        $etiqBoton = "refrescar";

        if (SESSION("empresaO")) {
                $idOferta = 0; //si se consulta por empresa, se ignora el parámetro oferta
                $etiqBuscador = ETIQ_BUSCADOR_OFERTAS2;
                $etiqBoton = "";
                $sector = 0;
                $poblacion = 0;
                if (!$empresa=Empresa::obtenerPorIdEmpresa(SESSION("empresaO"))) {
                     $smarty->assign('mensajeCentral',ETIQ_EMPRESA_NO_ENCONTRADA);
                     $smarty->assign('class_mensajeCentral','class="errorEmpPPal"');
                     $smarty->display(TPL_MENSAJE);
                     $smarty->assign('mostrar_texto',false);
                     $YEmpresa = "";
                     $masEmpresa = "";
                } else {
                     $smarty->assign('prefijo','ofertas de ');
                     $smarty->assign('mensajeCentral',$empresa->obtenerCampo("empresa"));
                     $smarty->assign('class_mensajeCentral','class="ofertasEmpresa"');
                     $smarty->display(TPL_MENSAJE);
                     $YEmpresa = "&empresa=".SESSION("empresaO");
                     $masEmpresa = "?empresa=".SESSION("empresaO");
               }
        } else {
            $YEmpresa = "";
            $masEmpresa = "";
        }


        if ($idOferta) {
                $etiqBuscador = ETIQ_BUSCADOR_OFERTAS2;
                $etiqBoton = "";
                $sector = 0;
                $poblacion = 0;
                if (!$oferta=Oferta::obtenerPorIdOferta($idOferta)) {
                     $smarty->assign('mensajeCentral',ETIQ_OFERTA_NO_ENCONTRADA);
                     $smarty->assign('class_mensajeCentral','class="errorEmpPPal"');
                     $smarty->display(TPL_MENSAJE);
                     $smarty->assign('mostrar_texto',false);
                } else {
                     for($c=0; $c<count($campos); $c++)
                         $smarty->assign($campos[$c]["campo"],$oferta->obtenerCampo($campos[$c]["bd"]));
                     $empresa = Empresa::obtenerPorIdEmpresa($oferta->obtenerCampo("idEmpresa"));
                     $smarty->assign("empresa",$empresa->obtenerCampo("empresa"));
                     $smarty->assign("link_empresa",completarURL(PAG_PPAL_EMPRESAS."?empresa=".$oferta->obtenerCampo("idEmpresa")));
                     $smarty->display(TPL_VISTA_OFE);
               }
               $smarty->assign('prefijo','');
               $smarty->assign('mensajeCentral','');
               $smarty->assign('class_mensajeCentral','class="ofertasEmpresa"');
               $smarty->display(TPL_MENSAJE);
        }

        if (!SESSION("empresaO")) mostrar_buscador(PAG_PPAL_OFERTAS, $etiqBuscador, $etiqBoton, $sector, $poblacion);

        if (!$idOferta) {

          $numRegistros = count($datos);

          if ($numRegistros > 0) {

              for($r=0; $r<$numRegistros; $r++) {
                  for($c=0; $c<count($campos); $c++)
                       $smarty->assign($campos[$c]["campo"],$datos[$r][$campos[$c]["bd"]]);
                  if (!SESSION("empresaO")) {
                      $empresa = Empresa::obtenerPorIdEmpresa($datos[$r]["idEmpresa"]);
                      $smarty->assign("empresa",$empresa->obtenerCampo("empresa"));
                  }
                  $smarty->assign("link_empresa",completarURL(PAG_PPAL_EMPRESAS."?empresa=".$datos[$r]["idEmpresa"]));
                  $smarty->display(TPL_VISTA_OFE);
              }

          } else {


                   $smarty->assign('prefijo','');
                   if (SESSION("empresaO")) {
                       if ($YEmpresa) {
                           $smarty->assign('mensajeCentral',ETIQ_NO_HAY_OFERTAS_EMPRESA);
                           $smarty->assign('class_mensajeCentral','class="errorEmpPPal"');
                           $smarty->display(TPL_MENSAJE);
                           $smarty->assign('mostrar_texto',false);
                       }
                   } else {
                       $smarty->assign('mensajeCentral',ETIQ_NO_HAY_OFERTAS);
                       $smarty->assign('class_mensajeCentral','class="errorEmpPPal"');
                       $smarty->display(TPL_MENSAJE);
                       $smarty->assign('mostrar_texto',false);
                   }
//               }
          }

        } else {

              $smarty->assign('mostrar_texto',false);

        }
        
        if (SESSION("empresaO")) {
            $smarty->assign('prefijo','');
            $smarty->assign('mensajeCentral','');
            $smarty->assign('class_mensajeCentral','class="ofertasEmpresa"');
            $smarty->display(TPL_MENSAJE);
            mostrar_buscador(PAG_PPAL_OFERTAS, $etiqBuscador, $etiqBoton, $sector, $poblacion);
        }

        $smarty->assign('pagAct',SESSION("ofertas")->pagAct());
   	$smarty->assign('totPag',SESSION("ofertas")->totPag());
  	$smarty->assign('totReg',SESSION("ofertas")->totReg());
  	$smarty->assign('tamPag',SESSION("ofertas")->obtenerCampo("tamPag"));


        $smarty->assign('link_ini',completarURL(PAG_PPAL_OFERTAS."?inicio$YEmpresa"));
        $smarty->assign('link_ant',completarURL(PAG_PPAL_OFERTAS."?anterior$YEmpresa"));
        $smarty->assign('link_sgt',completarURL(PAG_PPAL_OFERTAS."?siguiente$YEmpresa"));
        $smarty->assign('link_fin',completarURL(PAG_PPAL_OFERTAS."?final$YEmpresa"));

   	$smarty->assign('action',completarURL(PAG_PPAL_OFERTAS.$masEmpresa));

        $smarty->display(TPL_SUBPIE_PPAL);

        $smarty->assign('normativa',completarURL(PAG_PPAL_NORMATIVA));
	$smarty->display(TPL_PIE_PPAL);

}

?>