<?php
require_once("comunes/config.php");

$nivel = chequearConexion();

cabeceraPrincipal($nivel);

$smarty = nuevaPlantilla();

echo '<div id="contenidos">';

      $smarty->display(TPL_TEXTO_ASOCIACION);

      include(PAG_GALERIA_NOTICIAS);

echo '</div>';

include(PAG_GALERIA_OFERTAS);

$smarty->assign('normativa',completarURL(PAG_PPAL_NORMATIVA));
$smarty->display(TPL_PIE_PPAL);

?>