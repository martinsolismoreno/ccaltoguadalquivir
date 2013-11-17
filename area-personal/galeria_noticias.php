<?php

////////////////// LOGICA DE PROCESO /////////////////////////////////////////////////////////////////////////////////////////////////////////

//esta primera parte es para cuando se llama mediante una llamada POST con ese par�metro para recibir una respuesta JSON
if ((isset($_POST["nuevaNoticia"]))||(isset($_POST["ultimasNoticias"]))||(isset($_POST["iniciarNoticias"]))) {

  require_once("../comunes/config.php"); //como se llama remotamente, no a trav� de un p�gina que recoge el HTML necesita conocer la configuarci�n general

  chequearConexion(); // sobre todo por iniciar sesi�n, ya que esta parte es para llamadas remotas u por tanto en este punto no tiene iniciada sesi�n y no conoce el SID.

  if (isset($_POST["nuevaNoticia"]))  obtenerNuevaNoticia(); //llamada habitual, perdir nueva noticia. Se inicia si es la primera vez
  elseif (isset($_POST["iniciarNoticias"])) { unset($_SESSION["galNoticias"]); obtenerNuevaNoticia(); } //borramos variable y como si fuese la primera vez
  elseif (!SESSION("galNoticias")) obtenerNuevaNoticia(); //si no se piden las anteriores opciones, es xq se piden la ultimas noticas sin actualizar
                                                                    //y si no se hab�a llamado ninguna vez, es como si fuese iniciarNoticias. Si se ya se hizo una llamada
                                                                    //anterior, no se hace nada m�s que pasar al bucle que formater� la salida...

  unset($_POST["nuevaNoticia"]);
  unset($_POST["ultimasNoticias"]);

  if (SESSION_CLAVE("galNoticias","noticias")) {
       $salida = "{resultado: \"ok\", noticias: [";
       foreach(SESSION_CLAVE("galNoticias","noticias") as $noticia) {
               if ($noticia['idNoticia']>0) $urlmas = completarURL(PAG_PPAL_NOTICIAS."?noticia=".$noticia['idNoticia']);
               else $urlmas = "#";
              $salida.= "{ numero: \"".$noticia['idNoticia']."\",
                            titular: \"".htmlentities($noticia['titular'], ENT_QUOTES, 'ISO-8859-1')."\",
                            resumen: \"".htmlentities($noticia['resumen'], ENT_QUOTES, 'ISO-8859-1')."\",
                              texto: \"".htmlentities($noticia['textoNot'], ENT_QUOTES, 'ISO-8859-1')."\",
                               link: \"".$noticia['linkRef']."\",
                                mas: \"".$urlmas."\",
                              fecha: \"".$noticia['fechaNot']."\"},";
       }
       $salida = preg_replace("/,$/","",$salida);
       $salida .= " ] }";
       echo $salida;
  } else echo "{resultado: \"error\",  noticia: \"\"}";

// y aqu� es la l�gica de tratamiento cuando se llama la p�gina sin m�s para que devuelva c�digo HTML
//solo funciona cuando no hay javascript, enviando de esa forma una noticia cada vez que se entra y sale de la p�gina principal
//y en la plantilla se avisa de que javascript est� desactivado
} else {

   //esta parte no inicia sesi�n porque siempre es llamada desde una p�gina interna

  $javascript = comprobarJavascript();

  $smarty = nuevaPlantilla();
  $smarty->assign('javascript',$javascript);

  if (!$javascript) {

       obtenerNuevaNoticia();
       $smarty->assign('comprobarjs',completarURL(PAG_PPAL.'?comprobarjs'));
       $smarty->assign('titular',$_SESSION["galNoticias"]["noticias"][0]['titular']);
       if (($_SESSION["galNoticias"]["noticias"][0]['idNoticia'])>0)
           $smarty->assign('link_titular',completarURL(PAG_PPAL_NOTICIAS."?noticia=".$_SESSION["galNoticias"]["noticias"][0]['idNoticia']));
       else
           $smarty->assign('link_titular',"#");
       $smarty->assign('fecha',$_SESSION["galNoticias"]["noticias"][0]['fechaNot']);
       $smarty->assign('resumen',$_SESSION["galNoticias"]["noticias"][0]['resumen']);
       $smarty->assign('texto',$_SESSION["galNoticias"]["noticias"][0]['textoNot']);
       $smarty->assign('link',$_SESSION["galNoticias"]["noticias"][0]['linkRef']);

  }

  $smarty->display(TPL_GAL_NOT);


}

/////////////////// FUNCIONES /////////////////////////////////////////////////////////////////////////////////////////////


function obtenerNuevaNoticia() {

         if (!SESSION("galNoticias")) { //si no existe a�n la variable galer�aNoticias para esta sesi�n, la creamos
             $_SESSION["galNoticias"]["consulta"] = Consulta::obtenerNoticias();
             $_SESSION["galNoticias"]["noticias"] = array();
             $_SESSION["galNoticias"]["numPet"] = 0;
             $_SESSION["galNoticias"]["consulta"]->pagFinal();
         }
         $_SESSION["galNoticias"]["numPet"]++;
         $ultPeticiones = ($_SESSION["galNoticias"]["numPet"]<11) ? $_SESSION["galNoticias"]["numPet"] : 10;
         $primeraNoticia = (count($_SESSION["galNoticias"]["noticias"])>0) ? $_SESSION["galNoticias"]["noticias"][0] : "";
         do {
//             depurarNoticias();
             $consulta = siguienteNoticia(SESSION_CLAVE("galNoticias","consulta"));
             if (SESSION_CLAVE("galNoticias","consulta")->totReg()==0) {
                 $_SESSION["galNoticias"]["noticias"] = array();
                 $_SESSION["galNoticias"]["noticias"][] = noticiaInicial();
             } else incluirNoticia( $consulta[0],$ultPeticiones);
         } while (((($_SESSION["galNoticias"]["noticias"][0])==$primeraNoticia) AND (SESSION_CLAVE("galNoticias","consulta")->totReg()>1)) OR
                  ((count($_SESSION["galNoticias"]["noticias"])<$ultPeticiones) AND (SESSION_CLAVE("galNoticias","consulta")->totReg()>=$ultPeticiones)));

}

function siguienteNoticia($consultaNoticias) {
    if ($consultaNoticias->pagAct() < $consultaNoticias->totPag()) return $consultaNoticias->pagSiguiente();
    else return $consultaNoticias->pagInicial();
}

//comprueba que las noticias a�n existen o siguen vigentes
function depurarNoticias() {
     for($i=0; $i<count($_SESSION["galNoticias"]["noticias"]); $i++) {
             if (!$noticia=Noticia::obtenerPorIdNoticia($_SESSION["galNoticias"]["noticias"][$i]["idNoticia"])) array_splice($_SESSION["galNoticias"]["noticias"],$i,1);
         elseif (!$noticia->obtenerCampo("activa"))array_splice($_SESSION["galNoticias"]["noticias"],$i,1);
         elseif ($noticia !== $_SESSION["galNoticias"]["noticias"][$i]) array_splice($_SESSION["galNoticias"]["noticias"],$i,1,$noticia);
    }
}

//si la noticia no existe en el array que se mantiene con la noticia m�s actual y las n inmediatas anteriores,
//se incluye, y si existe, se modifica el array de forma que esa noticia sea la del �ndice 0, convirti�ndose en la actual
function incluirNoticia($noticia,$ultPeticiones) {
     $existe = false;
     for($i=0; $i<count($_SESSION["galNoticias"]["noticias"]); $i++) {
         if ($_SESSION["galNoticias"]["noticias"][$i]["idNoticia"]==$noticia["idNoticia"]) {
             $noticias = array_splice($_SESSION["galNoticias"]["noticias"],$i);
             array_splice($_SESSION["galNoticias"]["noticias"],0,0,$noticias);
             $existe = true;
         }
     }
     if (!$existe) array_unshift( $_SESSION["galNoticias"]["noticias"],$noticia);
     while (count($_SESSION["galNoticias"]["noticias"])>$ultPeticiones) array_pop($_SESSION["galNoticias"]["noticias"]);
     return $existe;
}

//esta es la primera noticia que se env�a y la �nica en caso que no haya noticias en la bd.
//si hay noticias en la bd no se mostrar� m�s de una vez (que se puede evitar, pero lo veo conveniente)
//as� si din�micamente se pierden todas las noticias, el usuario ver� que s�lo le aparece esta y por lo menos no se queda con la sensaci�n de que
//la web no ha terminado de cargarse o algo.
function noticiaInicial() {

    $fechaHoy = date("d")."/".date("m")."/".date("Y");
    return array(
                "idNoticia" => "0",
		"idUsuario" => "0",
		"titular"   => "�Este es tu portal de noticias!",
    	        "resumen"   => "Desde este portal del Centro Comercial Alto Guadalquivir usted puede dar a conocer hechos importantes y relevantes que suceden en nuestra comarca",
	 	"textoNot"  => "Queremos que este medio sea tambi�n un portal donde todos podamos compartir todo aquello que se considere de inter�s. Para ello s�lo tiene que remitirnos ".
                               "un correo con el titular de la noticia, un resumen de �sta, el texto en s� y alg�n link que considere que ampl�a la noticia o la fuente de �sta. ".
                               "Si se considera de inter�s general, ser� gustosamente publicada en nuestro portal.",
         	"linkRef"   => "mailto:noticias@ccaltoguadalquivir.es",
		"fechaNot"  => $fechaHoy,
		"activa"    => "1",
	 	"fecha"     => "");

}


?>