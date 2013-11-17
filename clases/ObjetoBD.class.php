<?php

abstract class ObjetoBD {

	protected $propiedades = array();

	static protected $patrones = array();

	public function __construct($objeto) {
                ObjetoBD::formatearFechas($objeto);
		foreach($objeto as $clave => $valor)
			if (array_key_exists($clave, $this->propiedades))
                                  if ($this->valorCorrecto($clave,$valor)) $this->propiedades[$clave]=$valor;
        }

	protected function valorCorrecto($patron,$valor) {
       		return (array_key_exists($patron, ObjetoBD::$patrones)) ? preg_match(ObjetoBD::$patrones[$patron],$valor):0;
        }

        protected static function formatearFechas(&$objeto) {

                foreach($objeto as $clave => $valor)
                        if (preg_match("/fecha/",$clave))
                            if ( $valor AND (preg_match(FECHABD,$valor))) {
                                 $objeto[$clave]=substr($valor,8,2)."/".substr($valor,5,2)."/".substr($valor,0,4);
                                 if ($objeto[$clave]=="00/00/0000") $objeto[$clave]="";
                            }
        }

        protected function convertirFechas() {
                foreach($this->propiedades as $clave => $valor)
                        if (preg_match("/fecha/",$clave))
                            if ( $valor AND (preg_match(FECHA,$valor))) {
                                 $this->propiedades[$clave]=substr($valor,6,4)."-".substr($valor,3,2)."-".substr($valor,0,2);
                            }
        }


	public function obtenerCampo($campo) {
                return (array_key_exists($campo, $this->propiedades)) ? $this->propiedades[$campo] : null;
	}

	public function arrayPropiedades() {
		$a = array();
		foreach($this->propiedades as $clave => $valor)
		        $a[$clave] = $valor;
                return $a;
        }


	protected static function conectar() {
		try {
			$conex = new PDO(DB_DSN, DB_USUARIO, DB_PASSWORD);
			$conex->setAttribute(PDO::ATTR_PERSISTENT, true);
			$conex->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);			
		} catch (PDOException $ex) {
			errorbd("Conexión a BD fallida: ".$ex->getMessage());
		}
		return $conex;
	}

	protected static function desconectar($conex) {
		$conex = "";
	}

}
?>