<?php

require_once("comunes/config.php");

// arrays que definen los campos que se van a mostrar en las cabeceras de la tablas y su correspondencia con el campo de bd que van a mostrar
$campos = array( array( "campo" => "empresa",     "bd"  => "empresa"),
                 array( "campo" => "descripcion", "bd"  => "descripcion"),
                 array( "campo" => "direccion",   "bd"  => "direccion"),
                 array( "campo" => "pedania",     "bd"  => "pedania"),
                 array( "campo" => "telefono1",   "bd"  => "telefono1"),
                 array( "campo" => "telefono2",   "bd"  => "telefono2"),
                 array( "campo" => "fax",         "bd"  => "fax"),
                 array( "campo" => "email",       "bd"  => "email"),
                 array( "campo" => "web",         "bd"  => "web"),
                 array( "campo" => "horario",     "bd"  => "horario")
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

         if (array_key_exists("empresa",$parametros)) $empresa=$parametros["empresa"];
         else $empresa = 0;

             if (array_key_exists("sector",$parametros)) $sector=$parametros["sector"];
         elseif (POST("sector")) $sector=POST("sector");
           else $sector = ((!POST("buscar")AND(SESSION("sectorE")))) ? SESSION("sectorE"):0;

             if (array_key_exists("poblacion",$parametros)) $poblacion=$parametros["poblacion"];
         elseif (POST("poblacion")) $poblacion=POST("poblacion");
           else $poblacion = ((!POST("buscar")AND (SESSION("poblacionE")))) ? SESSION("poblacionE"):0;

             if (array_key_exists("numreg",$parametros)) $tamPag=$parametros["numreg"];
         elseif (POST("numreg")) $tamPag=POST("numreg");
           else $tamPag = 0;

         $error="";
         if (!SESSION("empresas")||(POST("buscar"))||
              ((!POST("buscar"))&&(!POST("numreg"))&&(!POST("pagina"))&&(!$parametros))) {
             if ((!POST("buscar"))&&(!POST("numreg"))&&(!POST("pagina"))&&(!$parametros)) { $sector=0; $poblacion=0; }
             if (!$_SESSION["empresas"]=Consulta::obtenerEmpresas($sector,$poblacion)) $error=ETIQ_CONSULTA_FALLIDA;
             if (!$error) {
                 $_SESSION["sectorE"]=$sector;
                 $_SESSION["poblacionE"]=$poblacion;
                 $tamPag = ($tamPag) ? $tamPag : TAM_PAG_EMP_PPAL;
                 $_SESSION["empresas"]->modTamPag($tamPag);
             }
             $parametros["inicio"]=""; //damos de alta el parámetro "inicio" para que se muestre la primera página si no hay error...
         } elseif ($tamPag) SESSION("empresas")->modTamPag($tamPag);
             else  SESSION("empresas")->actualizarPag();

         // si no hay errores, la peticion es correcta, hay privilegios y la consulta se ha establecido o ya se había creado... se muestran los datos...
         if (!$error) {
                         //los parámetros de movimiento de página tienen este orden de precedencia, inicio el primero xq cuando hay nueva consulta, lo activamos para que se inicie ahí
       		         if (array_key_exists("inicio",$parametros))    listadoNoticias($nivel, SESSION("empresas")->pagInicial(),$campos, $empresa, $sector, $poblacion);
		     elseif (array_key_exists("pagina",$parametros))    listadoNoticias($nivel, SESSION("empresas")->irPag($parametros["pagina"]),$campos,  $empresa, $sector, $poblacion);
		     elseif (POST("pagina"))                            listadoNoticias($nivel, SESSION("empresas")->irPag(POST("pagina")),$campos,  $empresa, $sector, $poblacion);
		     elseif (array_key_exists("final",$parametros))     listadoNoticias($nivel, SESSION("empresas")->pagFinal(),$campos, $empresa, $sector, $poblacion);
                     elseif (array_key_exists("anterior",$parametros))  listadoNoticias($nivel, SESSION("empresas")->pagAnterior(),$campos, $empresa, $sector, $poblacion);
		     elseif (array_key_exists("siguiente",$parametros)) listadoNoticias($nivel, SESSION("empresas")->pagSiguiente(),$campos, $empresa, $sector, $poblacion);
                       else                                             listadoNoticias($nivel, SESSION("empresas")->actualizarPag(),$campos, $empresa, $sector, $poblacion); //si no se indicó ningún parámetro de pagina, anterior, etc...
                                                                                                                                      //por lo que si había consulta ya, se refresca la página nada más
        } else { //y aquí se muestran los errores que se han detectado

              informacion($error,INFORMACION,"",PAG_PPAL,ETIQ_LINK_PAG_PPAL);
        }



/////////////////// FUNCIONES /////////////////////////////////////////////////////////////////////////////////////////////

function listadoNoticias($nivel, $datos, $campos, $idEmpresa, $sector, $poblacion) {

        cabeceraPrincipal($nivel);

        $smarty = nuevaPlantilla();

        $etiqBuscador = ETIQ_BUSCADOR_EMPRESAS1;
        $etiqBoton = "refrescar ";

        if ($idEmpresa) {
                $etiqBuscador = ETIQ_BUSCADOR_EMPRESAS2;
                $etiqBoton = "";
                if (!$empresa=Empresa::obtenerPorIdEmpresa($idEmpresa)) {
                     $smarty->assign('mensajeCentral',ETIQ_EMPRESA_NO_ENCONTRADA);
                     $smarty->assign('class_mensajeCentral','class="errorEmpPPal"');
                     $smarty->display(TPL_MENSAJE);
                     $smarty->assign('mostrar_texto',false);
                } else {
                     for($c=0; $c<count($campos); $c++)
                         $smarty->assign($campos[$c]["campo"],$empresa->obtenerCampo($campos[$c]["bd"]));
                     completarCampos($smarty, $empresa->obtenerCampo("idEmpresa"), $empresa->obtenerCampo("idPoblacion"));
                     $smarty->display(TPL_VISTA_EMP);
                     $sector = 0;
                     $poblacion = 0;
               }
               $smarty->assign('prefijo','');
               $smarty->assign('mensajeCentral','');
               $smarty->assign('class_mensajeCentral','class="ofertasEmpresa"');
               $smarty->display(TPL_MENSAJE);
        }

        mostrar_buscador(PAG_PPAL_EMPRESAS, $etiqBuscador, $etiqBoton, $sector, $poblacion);

        if (!$idEmpresa) {

          $numRegistros = count($datos);
  
          if ($numRegistros > 0) {

              for($r=0; $r<$numRegistros; $r++) {
                  for($c=0; $c<count($campos); $c++)
                       $smarty->assign($campos[$c]["campo"],$datos[$r][$campos[$c]["bd"]]);
              completarCampos($smarty, $datos[$r]["idEmpresa"], $datos[$r]["idPoblacion"]);
              $smarty->display(TPL_VISTA_EMP);
              }

          } else {
               $smarty->assign('mensajeCentral',ETIQ_NO_HAY_EMPRESAS);
               $smarty->assign('class_mensajeCentral','class="errorEmpPPal"');
               $smarty->display(TPL_MENSAJE);
               $smarty->assign('mostrar_texto',false);
          }

        } else {

              $smarty->assign('mostrar_texto',false);

        }

        $smarty->assign('pagAct',SESSION("empresas")->pagAct());
   	$smarty->assign('totPag',SESSION("empresas")->totPag());
  	$smarty->assign('totReg',SESSION("empresas")->totReg());
  	$smarty->assign('tamPag',SESSION("empresas")->obtenerCampo("tamPag"));


        $smarty->assign('link_ini',completarURL(PAG_PPAL_EMPRESAS."?inicio"));
        $smarty->assign('link_ant',completarURL(PAG_PPAL_EMPRESAS."?anterior"));
        $smarty->assign('link_sgt',completarURL(PAG_PPAL_EMPRESAS."?siguiente"));
        $smarty->assign('link_fin',completarURL(PAG_PPAL_EMPRESAS."?final"));

   	$smarty->assign('action',completarURL(PAG_PPAL_EMPRESAS));

        $smarty->display(TPL_SUBPIE_PPAL);

        $smarty->assign('normativa',completarURL(PAG_PPAL_NORMATIVA));
	$smarty->display(TPL_PIE_PPAL);

}  

function completarCampos($smarty, $idEmpresa, $idPoblacion) {

        $nombreArchivo = DIR_ARCH_EMP.PREFIJO_ARCH_EMP.$idEmpresa;
        if (file_exists(RUTA_ABS.$nombreArchivo.".gif")) $nombreArchivo.=".gif";
        elseif (file_exists(RUTA_ABS.$nombreArchivo.".jpg")) $nombreArchivo.=".jpg";
        else $nombreArchivo = DIR_ARCH_EMP.PREFIJO_ARCH_EMP.".gif";
        $smarty->assign('imagen',$nombreArchivo);
        $poblacion = Poblacion::obtenerPorIdPoblacion($idPoblacion);
        $smarty->assign("poblacion",$poblacion->obtenerCampo("poblacion"));
        $smarty->assign("ofertas",completarURL(PAG_PPAL_OFERTAS."?empresa=".$idEmpresa));

}

?>