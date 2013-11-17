<?php

require_once(RUTA_ABS."/clases/ObjetoBD.class.php");

class Sector extends ObjetoBD {

	protected $propiedades = array(
		"idSector" => "",
		"sector" => "",
	);
	
	static protected $patrones = array(
		"idSector" => EXREG_idSector,
		"sector" => EXREG_sector,
	 );

	protected function valorCorrecto($patron,$valor) {
    		return (array_key_exists($patron, Sector::$patrones)) ? preg_match(Sector::$patrones[$patron],$valor):0;
        }

	public static function obtenerPorIdSector($idSector) {
		$conex = parent::conectar();
		$consultaSQL = "SELECT * FROM ".(BD).".".T_SECTORES." WHERE idSector = :idSector";

		try {
			$consulta = $conex->prepare($consultaSQL);
			$consulta->bindValue(":idSector", $idSector, PDO::PARAM_INT);
			$consulta->execute();
			$registro = $consulta->fetch();
			parent::desconectar($conex);
			if ($registro) return new Sector($registro);
		} catch (PDOException $ex) {
			parent::desconectar($conex);
			errorbd("Consulta por idSector fallida en Sectores: ".$ex->getMessage(),true);
		}

	}
	
	public static function obtenerListadoDeSectores() {

		$conex = parent::conectar();
		$consultaSQL = "SELECT * FROM ".(BD).".".(T_SECTORES);

		try {
			$consulta = $conex->prepare($consultaSQL);
			$consulta->execute();
			$registros = array();
			foreach ($consulta->fetchAll() as $registro) $registros[$registro["idSector"]] = htmlentities($registro["sector"], ENT_NOQUOTES, 'ISO-8859-1');
  			parent::desconectar($conex);
			return $registros;
		} catch (PDOException $ex) {
			parent::desconectar($conex);
			errorbd("Consulta de listado de sectores fallido: ".$ex->getMessage(),true);
		}
	}



	public function grabarEnBD() {

		$conex = parent::conectar();
		$consultaSQL = "INSERT INTO ".(BD).".".(T_SECTORES)."(
				sector)
				VALUES (
				:sector)";

		try {
			$consulta = $conex->prepare($consultaSQL);
			$consulta->bindValue(":sector", $this->propiedades["sector"], PDO::PARAM_STR);
			$consulta->execute();
			parent::desconectar($conex);
		} catch (PDOException $ex) {
			parent::desconectar($conex);
			errorbd('Error al grabar Sector en la BD: '.$ex->getMessage(),true);
		}
	}

	public function actualizarEnBD() {

		$conex = parent::conectar();
		$consultaSQL = "UPDATE ".(BD).".".(T_SECTORES)." SET
				sector = :sector
				WHERE idSector = :idSector";

		try {
			$consulta = $conex->prepare($consultaSQL);
			$consulta->bindValue(":idSector", $this->propiedades["idSector"], PDO::PARAM_INT);
                        $consulta->bindValue(":sector", $this->propiedades["sector"], PDO::PARAM_STR);
			$consulta->execute();
			parent::desconectar($conex);
		} catch (PDOException $ex) {
			parent::desconectar($conex);
			errorbd('Error al actualizar Sector en la BD: '.$ex->getMessage(), true);
		}
	}

	public function borrarDeBD() {

		$conex = parent::conectar();
		$consultaSQL = "DELETE FROM ".(BD).".".(T_SECTORES).
			       " WHERE idSector = :idSector";

		try {
			$consulta = $conex->prepare($consultaSQL);
			$consulta->bindValue(":idSector", $this->propiedades["idSector"], PDO::PARAM_INT);
			$consulta->execute();
			parent::desconectar($conex);
		} catch (PDOException $ex) {
			parent::desconectar($conex);
			errorbd('Error al borrar Sector en la BD: '.$ex->getMessage(), true);
		}
	}
}
?>