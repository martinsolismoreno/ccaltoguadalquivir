<?php

////////////////// LOGICA DE PROCESO /////////////////////////////////////////////////////////////////////////////////////////////////////////

//esta primera parte es para cuando se llama mediante una llamada POST con ese par�metro para recibir una respuesta JSON
if (isset($_POST["moverOfertas"])) {

  if (($_POST["moverOfertas"] != "anterior")AND($_POST["moverOfertas"] != "siguiente")) 
       return "{resultado: \"error\",  imagenes: \"\"}";

  require_once("../comunes/config.php"); //como se llama remotamente, no a trav� de un p�gina que recoge el HTML necesita conocer la configuarci�n general

  chequearConexion(); // sobre todo por iniciar sesi�n, ya que esta parte es para llamadas remotas u por tanto en este punto no tiene iniciada sesi�n y no conoce el SID.

  $imagenes = obtenerImagenes($_POST["moverOfertas"]);
  unset($_POST["moverOfertas"]);

  if ($imagenes) {

          $salida = "{resultado: \"ok\",  imagenes: [";
          $i=0;
          for($i=0; $i<count($imagenes); $i++) {
              if ($i>0) $salida.=", {";
              else $salida.= " {";
              foreach($imagenes[$i] as $clave => $valor) {
                   if ($clave=="src") $salida.=" $clave: \"$valor\" ";
                   else  $salida.=", $clave: \"$valor\" ";
              }
              $salida.=" }";
          }
          $salida.=" ]}";
          echo $salida;

  } else echo "{resultado: \"error\",  imagenes: \"\"}";

// y aqu� es la l�gica de tratamiento cuando se llama la p�gina sin m�s para que devuelva c�digo HTML
} else {

  //esta parte no inicia sesi�n porque siempre es llamada desde una p�gina interna

  $javascript = comprobarJavascript();

  if ($javascript) $imagenes = obtenerImagenes();
  else  $imagenes = obtenerImagenes("siguiente");

  $smarty = nuevaPlantilla();

  $smarty->assign('javascript',$javascript);

  foreach($imagenes as $i => $imagen) {
      $smarty->assign('img_oferta'.($i+1),$imagen["src"]);
      $smarty->assign('link_oferta'.($i+1),$imagen["link"]);
  }

  $smarty->display(TPL_GAL_OFE);

}

/////////////////// FUNCIONES /////////////////////////////////////////////////////////////////////////////////////////////


function obtenerImagenes($movimiento="") {
  
         if (!SESSION("galOfertas")) { //si no existe a�n la variable galer�aOfertas, la creamos
             $_SESSION["galOfertas"] = Consulta::obtenerOfertasPagPpal();
             SESSION("galOfertas")->pagInicial();
         }else {   if ($movimiento=="anterior")  anteriorOferta(SESSION("galOfertas"));
               elseif ($movimiento=="siguiente") siguienteOferta(SESSION("galOfertas"));
               else SESSION("galOfertas")->actualizarPag();
         }

         $pagOferta1 = SESSION("galOfertas")->pagAct(); //apuntamos la p�gina (n�mero de registro, xq en este caso el tama�o p�gina es 1) d�nde est� la que se considera primera oferta
         $idOfertas = array();  //en este array incluiremos los idOferta que vamos a necesitar

         global $archivosNoEncontrados;
         $archivosNoEncontrados=0;  //esta variable global servir� de apoyo en las funciones que revisan los ficheros ya que el total de registros disponibles puede disminuir si
                                    //resulta que por alg�n problema no encontramos su fichero asociado en el disco, diciendo en ese caso que a tenemos a lo mejor 3 ofertas disponibles
                                    //cuando realmente son 2 porque de 1 nos falta su fichero por alg�n extra�o motivo

        while ((count($idOfertas)<(SESSION("galOfertas")->totPag()-$GLOBALS["archivosNoEncontrados"])&&(count($idOfertas)<4))) { //hasta rellenar 3 im�gens
                                                                                                                                 //o el total de las que existan, claro (excluyendo fichos no encontrados)
                $idOfertas = incluirOferta($idOfertas,SESSION("galOfertas"));
                siguienteOferta(SESSION("galOfertas"));
//                $idOfertas = revisarImagenes($idOfertas); //no aseguramos que mientras no se ha borrado ninguna, por eso lo reviso de nuevo ya que siguienteOferta actualiza los datos disponibles
       }


//       $idOfertas = revisarImagenes($idOfertas); //si al final se incluyen m�s im�genes de 3 porque el totPag es inferior, habr� imagenes que se borraron y
                                                 //esta funci�n se asegura que la que est�n en el array, a�n existen (o por si hay alg�n fallo, claro)
                                                 //es como una revisi�n final al terminar el bucle de que todo sigue siendo coherente

       $salida = "";
       for($i=0; $i<count($idOfertas); $i++) {  //este bucle dar� un array con los nombres de los archivos y un link a la p�gina que le puede mostrar m�s datos de dicha oferta
           $nombreArchivo=comprobarArchivo($idOfertas[$i]);
           if ($nombreArchivo)
           $salida[] = array( "src" => RAIZ.$nombreArchivo, "link" => completarURL(PAG_PPAL_OFERTAS."?oferta=".$idOfertas[$i]));
       }


      $i=1;
      while (count($salida)<3) { //y por �ltimo si no se han completado 3 im�genes que es lo que mostramos en la p�gina principal, lo relleno con imagenes preparadas por defecto
             $salida[] = array( "src" => RAIZ.DIR_ARCH_OFE.PREFIJO_ARCH_OFE."xdefecto$i.gif", "link" => "#");
             $i++;
      }


     if ($pagOferta1<=SESSION("galOfertas")->totPag()) SESSION("galOfertas")->irPag($pagOferta1); //al final devolvemos el testigo a la que se consider� pagina inicial, la de la galOfertas.
                                                                                                  //incluso con borrados, la imagen1 siempre ser� la que corresponda a la p�gina se�alada
     else SESSION("galOfertas")->pagInicial();                                                    //, a no ser que queden incluso menos p�ginas de las que hab�a al entrar, y por tanto
                                                                                                  // en ese caso nos encontraremos en la p�gina inicial, esa va a ser la de la imagen mostrada en galOfertas

     return $salida;
     
}


function anteriorOferta($ofertas) {
    if ($ofertas->pagAct() > 1) $ofertas->pagAnterior();
    else $ofertas->pagFinal();
}

function siguienteOferta($ofertas) {
    if ($ofertas->pagAct() < $ofertas->totPag()) $ofertas->pagSiguiente();
    else $ofertas->pagInicial();
}

function incluirOferta($idOfertas,$ofertas) {
    $oferta = $ofertas->recuperarPag();
    if (count($oferta)>0) {
        $archivo = comprobarArchivo($oferta[0]["idOferta"]);
        if (($archivo)&&(array_search($oferta[0]["idOferta"],$idOfertas)===false))
             $idOfertas[] = $oferta[0]["idOferta"];
    }
    return $idOfertas;
}


function revisarImagenes($idOfertas) {
    $idOfertasRevisadas=array();
    for($i=0;$i<count($idOfertas);$i++) {
        $archivo = comprobarArchivo($idOfertas[$i]);
        if ($archivo) $idOfertasRevisadas[] = $idOfertas[$i];
    }
    return $idOfertasRevisadas;
}

function comprobarArchivo($idOferta) {
      $nombreArchivo = DIR_ARCH_OFE.PREFIJO_ARCH_OFE.$idOferta;
      if (file_exists(RUTA_ABS.$nombreArchivo.".gif")) return $nombreArchivo.".gif";
      elseif (file_exists(RUTA_ABS.$nombreArchivo.".jpg")) return $nombreArchivo.".jpg";
      else { $GLOBALS["archivosNoEncontrados"]++; return "";}
}

?>