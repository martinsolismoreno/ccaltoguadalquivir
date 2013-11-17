<?php

require_once("../comunes/config.php");

$nivel = chequearConexion();

if ($nivel == ANONIMO) {

    informacion(ETIQ_ACCESO_RESERVADO,AREA_RESTRINGIDA,"",PAG_REG_PPAL,ETIQ_LINK_REG,PAG_CONEX,ETIQ_BT_CONECTAR);

} else {


     if (($nivel==NORMAL)AND(SESSION_CAMPO("usuario","tipoUsuario")==T_CLIENTE)) {  //vamos directamente a sus datos personales porque es la unica opcion para un cliente normal
          redirigir(PAG_USUARIOS."?usuario=".SESSION_CAMPO("usuario","idUsuario"));
     } else {
	    if (isset($_POST["formUsuario"]))     redirigir(PAG_USUARIOS."?usuario=".SESSION_CAMPO("usuario","idUsuario"));
        elseif (isset($_POST["formEmpresa"]))     redirigir(PAG_EMPRESAS."?usuario=".SESSION_CAMPO("usuario","idUsuario"));
        elseif (isset($_POST["formOferta"]))      redirigir(PAG_OFERTAS);
        elseif (isset($_POST["listadoOfeEmp"]))   redirigir(PAG_LIST_OFE_EMP."?empresa=".Empresa::obtenerPorIdUsuario(SESSION_CAMPO("usuario","idUsuario"))->obtenerCampo("idEmpresa"));
        elseif (isset($_POST["formNoticia"]))     redirigir(PAG_NOTICIAS);
        elseif (isset($_POST["listadoNotUsu"]))   redirigir(PAG_LIST_NOT_USU."?usuario=".SESSION_CAMPO("usuario","idUsuario"));
        elseif (isset($_POST["listadoUsuarios"])) redirigir(PAG_LIST_USU);
        elseif (isset($_POST["listadoEmpresas"])) redirigir(PAG_LIST_EMP);
        elseif (isset($_POST["listadoOfertas"]))  redirigir(PAG_LIST_OFE);
        elseif (isset($_POST["listadoNoticias"])) redirigir(PAG_LIST_NOT);
        elseif (isset($_POST["listadoAccesos"]))  redirigir(PAG_LIST_ACC_GRAL);
        elseif (isset($_POST["listEmpAvalidar"])) redirigir(PAG_LIST_PTE);
        else    mostrarFormulario($nivel);
     }
}


/////////////////// FUNCIONES /////////////////////////////////////////////////////////////////////////////////////////////


function mostrarFormulario($nivel) {

	 $smarty = nuevaPlantilla();

	 $smarty->assign('tituloPagina',BIENVENIDO.SESSION_CAMPO("usuario","nombre"));

	 $mensajes=array();
	 $mensajes[]=ETIQ_SUBTIT_AREA_PERSONAL;
	 $smarty->assign('mensajes',$mensajes);

         $smarty->display(TPL_CABECERA);

 	 $smarty->assign('nivel',$nivel);
      	 $smarty->assign('action',completarURL(PAG_AREA_PERSONAL));

      	 if (Empresa::hayEmpresasPdtesValidar()) $smarty->assign('mostrarValidar',true);

	 $smarty->display(TPL_AREA_PERSONAL);

         $smarty->assign('linkPie',completarURL(PAG_PPAL));
 	 $smarty->assign('textoLinkPie',ETIQ_LINK_PAG_PPAL);

	 $smarty->display(TPL_PIE);

}

?>