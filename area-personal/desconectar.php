<?php


require_once("../comunes/config.php");

////////////////// LOGICA DE PROCESO /////////////////////////////////////////////////////////////////////////////////////////////////////////

$nivel = chequearConexion();

//esta primera parte es para cuando se llama mediante una llamada POST con ese par�metro (sin necesidad de informar nada m�s)
if (POST("soloChequear")) {

    if ($nivel == ANONIMO) {
        echo "{resultado: \"error\",  mensaje: \"".ETIQ_USUARIO_NO_CONECTADO."\"}";
    } else {
        eliminarSesion();
        echo "{resultado: \"ok\",  mensaje: \"".ETIQ_DECONEX_OK."\"}";
    }
//esta p�gina no tiene formulario, si se llama directamente por el navegador, en funci�n de si hay o no conexi�n, te desconecta y te informa.
} else {

    if ($nivel == ANONIMO) {
       informacion(ETIQ_USUARIO_NO_CONECTADO,INFORMACION,"","","",PAG_CONEX,ETIQ_BT_CONECTAR);
    } else {
       eliminarSesion();
       informacion(ETIQ_DECONEX_OK,HASTA_PRONTO);
    }

}

function eliminarSesion() {
       $_SESSION=array();
       session_destroy();
}
?>