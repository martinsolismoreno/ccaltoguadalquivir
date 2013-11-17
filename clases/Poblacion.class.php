<?php

require_once(RUTA_ABS."/clases/ObjetoBD.class.php");

class Poblacion extends ObjetoBD {

	protected $propiedades = array(
		"idPoblacion" => "",
		"poblacion" => "",
	);
	
	static protected $patrones = array(
		"idPoblacion" => EXREG_idPoblacion,
		"poblacion" => EXREG_poblacion,
	 );

	protected function valorCorrecto($patron,$valor) {
    		return (array_key_exists($patron, Poblacion::$patrones)) ? preg_match(Poblacion::$patrones[$patron],$valor):0;
        }

	public static function obtenerPorIdPoblacion($idPoblacion) {
		$conex = parent::conectar();
		$consultaSQL = "SELECT * FROM ".(BD).".".T_POBLACIONES." WHERE idPoblacion = :idPoblacion";

		try {
			$consulta = $conex->prepare($consultaSQL);
			$consulta->bindValue(":idPoblacion", $idPoblacion, PDO::PARAM_INT);
			$consulta->execute();
			$registro = $consulta->fetch();
			parent::desconectar($conex);
			if ($registro) return new Poblacion($registro);
		} catch (PDOException $ex) {
			parent::desconectar($conex);
			errorbd("Consulta por idPoblacion fallida en Poblaciones: ".$ex->getMessage(),true);
		}

	}
	
	public static function obtenerListadoDePoblaciones() {

		$conex = parent::conectar();
		$consultaSQL = "SELECT * FROM ".(BD).".".(T_POBLACIONES);

		try {
			$consulta = $conex->prepare($consultaSQL);
			$consulta->execute();
			$registros = array();
			foreach ($consulta->fetchAll() as $registro) $registros[$registro["idPoblacion"]] = htmlentities($registro["poblacion"], ENT_NOQUOTES, 'ISO-8859-1');
  			parent::desconectar($conex);
			return $registros;
		} catch (PDOException $ex) {
			parent::desconectar($conex);
			errorbd("Consulta de listado de poblaciones fallida: ".$ex->getMessage(),true);
		}
	}



	public function grabarEnBD() {

		$conex = parent::conectar();
		$consultaSQL = "INSERT INTO ".(BD).".".(T_POBLACIONES)."(
				poblacion)
				VALUES (
				:poblacion)";

		try {
			$consulta = $conex->prepare($consultaSQL);
			$consulta->bindValue(":poblacion", $this->propiedades["poblacion"], PDO::PARAM_STR);
			$consulta->execute();
			parent::desconectar($conex);
		} catch (PDOException $ex) {
			parent::desconectar($conex);
			errorbd('Error al grabar Poblacion en la BD: '.$ex->getMessage(),true);
		}
	}

	public function actualizarEnBD() {

		$conex = parent::conectar();
		$consultaSQL = "UPDATE ".(BD).".".(T_POBLACIONES)." SET
				poblacion = :poblacion
				WHERE idPoblacion = :idPoblacion";

		try {
			$consulta = $conex->prepare($consultaSQL);
			$consulta->bindValue(":idPoblacion", $this->propiedades["idPoblacion"], PDO::PARAM_INT);
                        $consulta->bindValue(":poblacion", $this->propiedades["poblacion"], PDO::PARAM_STR);
			$consulta->execute();
			parent::desconectar($conex);
		} catch (PDOException $ex) {
			parent::desconectar($conex);
			errorbd('Error al actualizar Poblacion en la BD: '.$ex->getMessage(), true);
		}
	}

	public function borrarDeBD() {

		$conex = parent::conectar();
		$consultaSQL = "DELETE FROM ".(BD).".".(T_POBLACIONES).
			       " WHERE idPoblacion = :idPoblacion";

		try {
			$consulta = $conex->prepare($consultaSQL);
			$consulta->bindValue(":idPoblacion", $this->propiedades["idPoblacion"], PDO::PARAM_INT);
			$consulta->execute();
			parent::desconectar($conex);
		} catch (PDOException $ex) {
			parent::desconectar($conex);
			errorbd('Error al borrar Poblacion en la BD: '.$ex->getMessage(), true);
		}
	}
}
?>