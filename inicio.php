<?php

require_once("comunes/config.php");

$nivel = chequearConexion();

cabeceraPrincipal($nivel);

$smarty = nuevaPlantilla();

echo '<div id="contenidos">';

      mostrar_buscador(PAG_PPAL_EMPRESAS, ETIQ_BUSCADOR_EMPRESAS2);

      $smarty->assign('link_conectarsocio',completarURL(PAG_CONEX."?pruebasocio"));
      $smarty->assign('link_conectargestor',completarURL(PAG_CONEX."?pruebagestor"));
      $smarty->assign('link_informacion',completarURL(PAG_PPAL_INFORMACION));

      $smarty->display(TPL_TEXTO_INICIO);

      include(PAG_GALERIA_NOTICIAS);

echo '</div>';

include(PAG_GALERIA_OFERTAS);

$smarty->assign('normativa',completarURL(PAG_PPAL_NORMATIVA));
$smarty->display(TPL_PIE_PPAL);

?>
