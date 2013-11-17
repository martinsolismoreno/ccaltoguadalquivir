<?php

require_once(RUTA_ABS."/clases/ObjetoBD.class.php");

class Acceso extends ObjetoBD {

	public $propiedades = array(
		"idUsuario" => "",
		"pagina" => "",
		"numVisitas" => "",
		"ultAcceso" => ""
	);

/////////////////////////////////////////////////////////

	static protected $patrones = array(
               "idUsuario" => EXREG_idUsuario,
               "pagina" => EXREG_pagina,
               "numVisitas" => EXREG_numVisitas,
               "ultAcceso" => EXREG_fecha
	 );

	protected function valorCorrecto($patron,$valor) {
    		return (array_key_exists($patron, Acceso::$patrones)) ? preg_match(Acceso::$patrones[$patron],$valor):0;
        }

/////////////////////////////////////////////////////////


        public function grabarEnBD() {

		$conex = parent::conectar();
		$consultaSQL = "SELECT * FROM  ".(BD).".".(T_ACCESOS) .
			       " WHERE idUsuario = :idUsuario AND pagina = :pagina";

		try {
			$consulta = $conex->prepare($consultaSQL);
			$consulta->bindValue(":idUsuario", $this->propiedades["idUsuario"], PDO::PARAM_INT);
			$consulta->bindValue(":pagina", $this->propiedades["pagina"], PDO::PARAM_STR);
			$consulta->execute();
			if ($registro = $consulta->fetch()) {
				$consultaSQL =  "UPDATE ".(BD).".".(T_ACCESOS)." SET numVisitas = numVisitas + 1".
					        " WHERE idUsuario = :idUsuario AND pagina = :pagina";
			} else {

				$consultaSQL =  "INSERT INTO ".(BD).".".(T_ACCESOS).
						"(idUsuario, pagina, numVisitas) VALUES (:idUsuario,:pagina, 1)";
			}
			$consulta = $conex->prepare($consultaSQL);

			$consulta->bindValue(":idUsuario", $this->propiedades["idUsuario"], PDO::PARAM_INT);
			$consulta->bindValue(":pagina", $this->propiedades["pagina"], PDO::PARAM_STR);
			$consulta->execute();
			parent::desconectar($conex);
		} catch (PDOException $ex) {
			parent::desconectar($conex);
			errorbd("Error al grabar acceso para miembro en la BD: ".$ex->getMessage());
		}
	}

	public static function borrarDeBD($idUsuario) {

		$conex = parent::conectar();
		$consultaSQL = "DELETE FROM  ".(BD).".".(T_ACCESOS).
			       " WHERE idUsuario = :idUsuario";

		try {
			$consulta = $conex->prepare($consultaSQL);
			$consulta->bindValue(":idUsuario",$idUsuario, PDO::PARAM_INT);
			$consulta->execute();
			parent::desconectar($conex);
		} catch (PDOException $ex) {
			parent::desconectar($conex);
			errorbd("Error al grabar acceso para miembro en la BD: ".$ex->getMessage());
		}
	}



}

?>