<?php

require_once("comunes/config.php");

$nivel = chequearConexion();

cabeceraPrincipal($nivel);

$smarty = nuevaPlantilla();

echo '<div id="contenidos">';

      $smarty->assign('nivel',$nivel);
      $smarty->display(TPL_TEXTO_NORMATIVA);

      include(PAG_GALERIA_NOTICIAS);

echo '</div>';

include(PAG_GALERIA_OFERTAS);

$smarty->display(TPL_PIE_PPAL);

?>