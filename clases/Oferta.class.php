<?php

require_once(RUTA_ABS."/clases/ObjetoBD.class.php");

class Oferta extends ObjetoBD {

	protected $propiedades = array(
		"idOferta" => "",
		"idEmpresa" => "",
		"oferta" => "",
		"fechaIni" => "",
		"fechaFin" => "",
		"textoOferta" => "",
		"textoCond" => "",
		"fichero" =>  "",
		"activa" =>  "",
		"fecha" => ""
	);

	static protected $patrones = array(
		"idOferta" => EXREG_idOferta,
		"idEmpresa" => EXREG_idEmpresa,
		"oferta" => EXREG_oferta,
		"fechaIni" => EXREG_fechaIni,
		"fechaFin" => EXREG_fechaFin,
		"textoOferta" => EXREG_textoOferta,
		"textoCond" => EXREG_textoCond,
		"fichero" => EXREG_fichero,
		"activa" => EXREG_activa,
		"fecha" => EXREG_fecha
	 );

	protected function valorCorrecto($patron,$valor) {
    		return (array_key_exists($patron, Oferta::$patrones)) ? preg_match(Oferta::$patrones[$patron],$valor):0;
        }

	public static function obtenerUltimaOferta($idEmpresa) {

		$conex = parent::conectar();
		$consultaSQL = "SELECT * FROM ".(BD).".".(T_OFERTAS)." WHERE idEmpresa = :idEmpresa ".
                               "ORDER BY fecha DESC LIMIT 0, 1";

		try {
			$consulta = $conex->prepare($consultaSQL);
			$consulta->bindValue(":idEmpresa", $idEmpresa, PDO::PARAM_INT);
			$consulta->execute();
			$registro = $consulta->fetch();
			parent::desconectar($conex);
			if ($registro) return new Oferta($registro);
		} catch (PDOException $ex) {
			parent::desconectar($conex);
			errorbd("Consulta por idEmpresa fallida en Ofertas: ".$ex->getMessage(),true);
		}
	}

	public static function obtenerOfertasEmpresa($idEmpresa) {

		$conex = parent::conectar();
		$consultaSQL = "SELECT * FROM ".(BD).".".(T_OFERTAS)." WHERE idEmpresa = :idEmpresa ";

		try {
			$consulta = $conex->prepare($consultaSQL);
			$consulta->bindValue(":idEmpresa", $idEmpresa, PDO::PARAM_INT);
			$consulta->execute();
                        $registros = array();
			foreach ($consulta->fetchAll() as $registro) $registros[] = new Oferta($registro); 
			parent::desconectar($conex);
			return $registros;
		} catch (PDOException $ex) {
			parent::desconectar($conex);
			errorbd("Consulta por idEmpresa fallida en Ofertas: ".$ex->getMessage(),true);
		}
	}



	public static function obtenerPorIdOferta($idOferta) {
		$conex = parent::conectar();
		$consultaSQL = "SELECT * FROM ".(BD).".".T_OFERTAS." WHERE idOferta = :idOferta";

		try {
			$consulta = $conex->prepare($consultaSQL);
			$consulta->bindValue(":idOferta", $idOferta, PDO::PARAM_INT);
			$consulta->execute();
			$registro = $consulta->fetch();
			parent::desconectar($conex);
			if ($registro) { return new Oferta($registro); }
		} catch (PDOException $ex) {
			parent::desconectar($conex);
			errorbd("Consulta por idOferta fallida en Ofertas: ".$ex->getMessage(),true);
		}

	}

        public function activarOferta() {
		$conex = parent::conectar();
		$consultaSQL = "UPDATE ".(BD).".".(T_OFERTAS)." SET
				activa = true
				WHERE idOferta = :idOferta";
		try {
			$consulta = $conex->prepare($consultaSQL);
			$consulta->bindValue(":idOferta", $this->propiedades["idOferta"], PDO::PARAM_INT);
			$consulta->execute();
			parent::desconectar($conex);
		} catch (PDOException $ex) {
			parent::desconectar($conex);
			errorbd('Error al activar Oferta en la BD: '.$ex->getMessage(), true);
		}
	}

       public function desactivarOferta() {
		$conex = parent::conectar();
		$consultaSQL = "UPDATE ".(BD).".".(T_OFERTAS)." SET
				activa = false
				WHERE idOferta = :idOferta";
		try {
			$consulta = $conex->prepare($consultaSQL);
			$consulta->bindValue(":idOferta", $this->propiedades["idOferta"], PDO::PARAM_INT);
			$consulta->execute();
			parent::desconectar($conex);
		} catch (PDOException $ex) {
			parent::desconectar($conex);
			errorbd('Error al activar Oferta en la BD: '.$ex->getMessage(), true);
		}
	}        

        public function activarFichero() {
		$conex = parent::conectar();
		$consultaSQL = "UPDATE ".(BD).".".(T_OFERTAS)." SET
				fichero = true
				WHERE idOferta = :idOferta";
		try {
			$consulta = $conex->prepare($consultaSQL);
			$consulta->bindValue(":idOferta", $this->propiedades["idOferta"], PDO::PARAM_INT);
			$consulta->execute();
			parent::desconectar($conex);
		} catch (PDOException $ex) {
			parent::desconectar($conex);
			errorbd('Error al activar Oferta en la BD: '.$ex->getMessage(), true);
		}
	}

       public function desactivarFichero() {
		$conex = parent::conectar();
		$consultaSQL = "UPDATE ".(BD).".".(T_OFERTAS)." SET
				fichero = false
				WHERE idOferta = :idOferta";
		try {
			$consulta = $conex->prepare($consultaSQL);
			$consulta->bindValue(":idOferta", $this->propiedades["idOferta"], PDO::PARAM_INT);
			$consulta->execute();
			parent::desconectar($conex);
		} catch (PDOException $ex) {
			parent::desconectar($conex);
			errorbd('Error al activar Oferta en la BD: '.$ex->getMessage(), true);
		}
	}        


	public function grabarEnBD() {

		$conex = parent::conectar();
		$consultaSQL = "INSERT INTO ".(BD).".".(T_OFERTAS)."(
				idEmpresa,
				oferta,
				fechaIni,
				fechaFin,
				textoOferta,
				textoCond,
				fichero,
                                activa)
				VALUES (
				:idEmpresa,
				:oferta,
				:fechaIni,
                                :fechaFin,
                                :textoOferta,
				:textoCond,
				:fichero,
                                :activa)";

		try {   $this->convertirFechas();
			$consulta = $conex->prepare($consultaSQL);
			$consulta->bindValue(":idEmpresa", $this->propiedades["idEmpresa"], PDO::PARAM_INT);
			$consulta->bindValue(":oferta", $this->propiedades["oferta"], PDO::PARAM_STR);
			$consulta->bindValue(":fechaIni", $this->propiedades["fechaIni"], PDO::PARAM_STR);
			$consulta->bindValue(":fechaFin", $this->propiedades["fechaFin"], PDO::PARAM_STR);
			$consulta->bindValue(":textoOferta", $this->propiedades["textoOferta"], PDO::PARAM_STR);
			$consulta->bindValue(":textoCond", $this->propiedades["textoCond"], PDO::PARAM_STR);
                        $consulta->bindValue(":fichero", false, PDO::PARAM_BOOL);
                        if ($this->propiedades["activa"]) $consulta->bindValue(":activa", true, PDO::PARAM_BOOL);
			else $consulta->bindValue(":activa", false, PDO::PARAM_BOOL);
			$consulta->execute();
			parent::desconectar($conex);
		} catch (PDOException $ex) {
			parent::desconectar($conex);
			errorbd('Error al grabar Oferta en la BD: '.$ex->getMessage(),true);
		}
	}

	public function actualizarEnBD() {

		$conex = parent::conectar();
		$consultaSQL = "UPDATE ".(BD).".".(T_OFERTAS)." SET
				idEmpresa = :idEmpresa,
				oferta = :oferta,
				fechaIni = :fechaIni,
                                fechaFin = :fechaFin,
                                textoOferta = :textoOferta,
                                textoCond = :textoCond,
                                fichero = :fichero,
                                activa = :activa
				WHERE idOferta = :idOferta";

		try {   $this->convertirFechas();
			$consulta = $conex->prepare($consultaSQL);
			$consulta->bindValue(":idOferta", $this->propiedades["idOferta"], PDO::PARAM_INT);
			$consulta->bindValue(":idEmpresa", $this->propiedades["idEmpresa"], PDO::PARAM_INT);
			$consulta->bindValue(":oferta", $this->propiedades["oferta"], PDO::PARAM_STR);
			$consulta->bindValue(":fechaIni", $this->propiedades["fechaIni"], PDO::PARAM_STR);
			$consulta->bindValue(":fechaFin", $this->propiedades["fechaFin"], PDO::PARAM_STR);
			$consulta->bindValue(":textoOferta", $this->propiedades["textoOferta"], PDO::PARAM_STR);
			$consulta->bindValue(":textoCond", $this->propiedades["textoCond"], PDO::PARAM_STR);
                        if ($this->propiedades["fichero"]) $consulta->bindValue(":fichero", true, PDO::PARAM_BOOL);
			else $consulta->bindValue(":fichero", false, PDO::PARAM_BOOL);
                        if ($this->propiedades["activa"]) $consulta->bindValue(":activa", true, PDO::PARAM_BOOL);
			else $consulta->bindValue(":activa", false, PDO::PARAM_BOOL);
			$consulta->execute();
			parent::desconectar($conex);
		} catch (PDOException $ex) {
			parent::desconectar($conex);
			errorbd('Error al actualizar Oferta en la BD: '.$ex->getMessage(), true);
		}
	}

	public function borrarDeBD() {

		$conex = parent::conectar();
		$consultaSQL = "DELETE FROM ".(BD).".".(T_OFERTAS).
			       " WHERE idOferta = :idOferta";

		try {
			$consulta = $conex->prepare($consultaSQL);
			$consulta->bindValue(":idOferta", $this->propiedades["idOferta"], PDO::PARAM_INT);
			$consulta->execute();
			parent::desconectar($conex);
		} catch (PDOException $ex) {
			parent::desconectar($conex);
			errorbd('Error al borrar Oferta en la BD: '.$ex->getMessage(), true);
		}
	}
	

}
?>