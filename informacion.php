<?php
require_once("comunes/config.php");

$nivel = chequearConexion();

$smarty = nuevaPlantilla();

$smarty->assign('tituloPagina',ETIQ_TIT_INFO);
         
$errores[]= "";

$smarty->assign('mensajes',$errores);

$smarty->display(TPL_CABECERA);

$smarty->display(TPL_INFORMACION);

$smarty->assign('linkPie',completarURL(PAG_PPAL));
$smarty->assign('textoLinkPie',ETIQ_LINK_PAG_PPAL);
$smarty->display(TPL_PIE);

?>
