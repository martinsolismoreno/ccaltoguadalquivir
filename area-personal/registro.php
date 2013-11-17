<?php

require_once("../comunes/config.php");

// arrays que definen los campos POST que se tratan en el formulario y su correspondencia con campos del objeto a tratar
// automatiza su tratamiento, oculta los nombres de las bases de datos, evito duplicidades en campos POST de distintos formulario
// y marco el patrón sobre el que chequear si el campo se ajusta a lo permitido o no
$campos = array( array( "post" => "F1_C1",  "bd" => "tipoUsuario"));

//en este se definen los campos POST obligatorios a completar
$camposRequeridos = array( "F1_C1" );


////////////////// LOGICA DE PROCESO /////////////////////////////////////////////////////////////////////////////////////////////////////////


if (chequearConexion() == ANONIMO) {  //si el usuario es anonimo, la opción es permitir el registro

     if (POST("enviar")) {
         $errores = array();
         $camposQueFaltan = array();
         comprobarCampos($campos,$camposRequeridos,$camposQueFaltan,$errores);
         if ($errores) {
            mostrarRegistro($camposQueFaltan, array(ETIQ_ELIJA_TIPO_USUARIO));
         } else {
             $_SESSION["tipoUsuario"]= POST("F1_C1");
	     redirigir(PAG_USUARIOS);
         }

     } else  mostrarRegistro(array(), array());

} else {

       informacion(ETIQ_USUARIO_REGISTRADO,INFORMACION,"",PAG_AREA_PERSONAL,ETIQ_LINK_AREA_PERSONAL);

}

/////////////////// FUNCIONES /////////////////////////////////////////////////////////////////////////////////////////////


function mostrarRegistro($camposQueFaltan, $errores, $modo=ALTA) {

	 $smarty = nuevaPlantilla();

         $smarty->assign('tituloPagina',ETIQ_TIT_REG_TIPUSU);
         
         if (!$errores) $errores[]= ETIQ_SUBTIT_REG_TIPUSU;
         else $smarty->assign('class_mensajes',CLAS_ERROR);

	 $smarty->assign('mensajes',$errores);

 	 $smarty->display(TPL_CABECERA);

         $smarty->assign('opciones_F1_C1', array( "cliente" => "Cliente", "socio" => "Socio"));
         $smarty->assign('F1_C1', "");

         $smarty->assign('enviar',ETIQ_BT_CONTINUAR);

      	 $smarty->assign('action',completarURL(PAG_REG_PPAL));

	 $smarty->display(TPL_REGISTRO);

  	 $smarty->assign('linkPie',completarURL(PAG_PPAL));
 	 $smarty->assign('textoLinkPie',ETIQ_LINK_PAG_PPAL);
	 $smarty->display(TPL_PIE);


} 

?>



