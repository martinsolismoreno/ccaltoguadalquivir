<?php

require_once(RUTA_ABS."/clases/ObjetoBD.class.php");

class EmpPoblSecUsu extends ObjetoBD {

	protected $propiedades = array(
		"idUsuario" => "",
		"empresa" => "",
		"poblacion" => "",
		"sector" => "",
		"usuario" => ""

	);

        static protected $patrones = array(
               "idUsuario" => EXREG_idUsuario,
	       "empresa" => EXREG_empresa,
	       "poblacion" => EXREG_poblacion,
	       "sector" => EXREG_sector,
               "usuario" => EXREG_usuario
	 );

	protected function valorCorrecto($patron,$valor) {
    		return (array_key_exists($patron, EmpPoblSecUsu::$patrones)) ? preg_match(EmpPoblSecUsu::$patrones[$patron],$valor):0;
        }
	
}

?>