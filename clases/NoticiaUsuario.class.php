<?php

require_once(RUTA_ABS."/clases/ObjetoBD.class.php");

class NoticiaUsuario extends ObjetoBD {

	protected $propiedades = array(
		"idNoticia" => "",
		"idUsuario" => "",
		"titular" => "",
		"usuario" => "",
		"fechaNot" => "",
		"activa" => ""

	);

        static protected $patrones = array(
		"idNoticia" => EXREG_idNoticia,
		"idUsuario" => EXREG_idUsuario,
		"titular" => EXREG_titular,
		"usuario" => EXREG_usuario,
		"fechaNot" => EXREG_fechaNot,
		"activa" => EXREG_activa
	 );

	protected function valorCorrecto($patron,$valor) {
    		return (array_key_exists($patron, NoticiaUsuario::$patrones)) ? preg_match(NoticiaUsuario::$patrones[$patron],$valor):0;
        }


}

?>