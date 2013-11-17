<?php

require_once(RUTA_ABS."/clases/ObjetoBD.class.php");

class UsuarioAcceso extends ObjetoBD {

	protected $propiedades = array(
		"idUsuario" => "",
		"usuario" => "",
		"pagina" => "",
		"numVisitas" => "",
		"ultAcceso" => ""

	);

        static protected $patrones = array(
               "idUsuario" => EXREG_idUsuario,
               "usuario" => EXREG_usuario,
               "pagina" => EXREG_pagina,
               "numVisitas" => EXREG_numVisitas,
               "ultAcceso" => EXREG_fecha
	 );

	protected function valorCorrecto($patron,$valor) {
    		return (array_key_exists($patron, UsuarioAcceso::$patrones)) ? preg_match(UsuarioAcceso::$patrones[$patron],$valor):0;
        }
	
}

?>