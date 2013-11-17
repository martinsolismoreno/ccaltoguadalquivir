<?php

require_once(RUTA_ABS."/clases/ObjetoBD.class.php");

class OfertaEmpresa extends ObjetoBD {

	protected $propiedades = array(
		"idOferta" => "",
		"idUsuario" => "",
		"idEmpresa" => "",
		"oferta" => "",
		"empresa" => "",
		"fechaIni" => "",
		"fechaFin" => "",
		"activa" => ""

	);

        static protected $patrones = array(
		"idOferta" => EXREG_idOferta,
		"idUsuario" => EXREG_idUsuario,
		"idEmpresa" => EXREG_idEmpresa,
		"oferta" => EXREG_oferta,
		"empresa" => EXREG_empresa,
		"fechaIni" => EXREG_fechaIni,
		"fechaFin" => EXREG_fechaFin,
		"activa" => EXREG_activa
	 );

	protected function valorCorrecto($patron,$valor) {
    		return (array_key_exists($patron, OfertaEmpresa::$patrones)) ? preg_match(OfertaEmpresa::$patrones[$patron],$valor):0;
        }


}

?>