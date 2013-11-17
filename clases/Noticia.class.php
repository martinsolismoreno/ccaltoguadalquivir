<?php

require_once(RUTA_ABS."/clases/ObjetoBD.class.php");

class Noticia extends ObjetoBD {

	protected $propiedades = array(
		"idNoticia" => "",
		"idUsuario" => "",
		"titular" => "",
    	        "resumen" => "",
	 	"textoNot" => "",
         	"linkRef" => "",
		"fechaNot" => "",
		"activa" => "",
		"fecha" => ""
	);

	static protected $patrones = array(
		"idNoticia" => EXREG_idNoticia,
		"idUsuario" => EXREG_idUsuario,
		"titular" => EXREG_titular,
    	        "resumen" => EXREG_resumen,
	 	"textoNot" => EXREG_textoNot,
         	"linkRef" => EXREG_linkRef,
		"fechaNot" => EXREG_fechaNot,
		"activa" => EXREG_activa,
		"fecha" => EXREG_fecha
	 );

	protected function valorCorrecto($patron,$valor) {
    		return (array_key_exists($patron, Noticia::$patrones)) ? preg_match(Noticia::$patrones[$patron],$valor):0;
        }


	public static function obtenerUltimaNoticia($idUsuario) {

		$conex = parent::conectar();
		$consultaSQL = "SELECT * FROM ".(BD).".".(T_NOTICIAS)." WHERE idUsuario = :idUsuario ".
                               "ORDER BY fecha DESC LIMIT 0, 1";

		try {
			$consulta = $conex->prepare($consultaSQL);
			$consulta->bindValue(":idUsuario", $idUsuario, PDO::PARAM_INT);
			$consulta->execute();
			$registro = $consulta->fetch();
			parent::desconectar($conex);
			if ($registro) return new Noticia($registro);
		} catch (PDOException $ex) {
			parent::desconectar($conex);
			errorbd("Consulta por idUsuario fallida en Noticias: ".$ex->getMessage(),true);
		}
	}

	public static function obtenerNoticiasUsuario($idUsuario) {

		$conex = parent::conectar();
		$consultaSQL = "SELECT * FROM ".(BD).".".(T_NOTICIAS)." WHERE idUsuario = :idUsuario ";

		try {
			$consulta = $conex->prepare($consultaSQL);
			$consulta->bindValue(":idUsuario", $idUsuario, PDO::PARAM_INT);
			$consulta->execute();
                        $registros = array();
			foreach ($consulta->fetchAll() as $registro) $registros[] = new Noticia($registro);
			parent::desconectar($conex);
			return $registros;
		} catch (PDOException $ex) {
			parent::desconectar($conex);
			errorbd("Consulta por idUsuario fallida en Noticias: ".$ex->getMessage(),true);
		}
	}


	public static function obtenerPorIdNoticia($idNoticia) {
		$conex = parent::conectar();
		$consultaSQL = "SELECT * FROM ".(BD).".".T_NOTICIAS." WHERE idNoticia = :idNoticia";

		try {
			$consulta = $conex->prepare($consultaSQL);
			$consulta->bindValue(":idNoticia", $idNoticia, PDO::PARAM_INT);
			$consulta->execute();
			$registro = $consulta->fetch();
			parent::desconectar($conex);
			if ($registro) return new Noticia($registro);
		} catch (PDOException $ex) {
			parent::desconectar($conex);
			errorbd("Consulta por idNoticia fallida en Noticias: ".$ex->getMessage(),true);
		}

	}

        public function activarNoticia() {
		$conex = parent::conectar();
		$consultaSQL = "UPDATE ".(BD).".".(T_NOTICIAS)." SET
				activa = true
				WHERE idNoticia = :idNoticia";
		try {
			$consulta = $conex->prepare($consultaSQL);
			$consulta->bindValue(":idNoticia", $this->propiedades["idNoticia"], PDO::PARAM_INT);
			$consulta->execute();
			parent::desconectar($conex);
		} catch (PDOException $ex) {
			parent::desconectar($conex);
			errorbd('Error al activar Noticia en la BD: '.$ex->getMessage(), true);
		}
	}

       public function desactivarNoticia() {
		$conex = parent::conectar();
		$consultaSQL = "UPDATE ".(BD).".".(T_NOTICIAS)." SET
				activa = false
				WHERE idNoticia = :idNoticia";
		try {
			$consulta = $conex->prepare($consultaSQL);
			$consulta->bindValue(":idNoticia", $this->propiedades["idNoticia"], PDO::PARAM_INT);
			$consulta->execute();
			parent::desconectar($conex);
		} catch (PDOException $ex) {
			parent::desconectar($conex);
			errorbd('Error al activar Noticia en la BD: '.$ex->getMessage(), true);
		}
	}

	public function grabarEnBD() {

		$conex = parent::conectar();
		$consultaSQL = "INSERT INTO ".(BD).".".(T_NOTICIAS)."(
				idUsuario,
				titular,
				resumen,
				textoNot,
				linkRef,
				fechaNot,
				activa)
				VALUES (
				:idUsuario,
				:titular,
				:resumen,
				:textoNot,
				:linkRef,
				:fechaNot,
                                :activa)";

		try {   $this->convertirFechas();
			$consulta = $conex->prepare($consultaSQL);
			$consulta->bindValue(":idUsuario", $this->propiedades["idUsuario"], PDO::PARAM_INT);
			$consulta->bindValue(":titular", $this->propiedades["titular"], PDO::PARAM_STR);
			$consulta->bindValue(":resumen", $this->propiedades["resumen"], PDO::PARAM_STR);
			$consulta->bindValue(":textoNot", $this->propiedades["textoNot"], PDO::PARAM_STR);
			$consulta->bindValue(":linkRef", $this->propiedades["linkRef"], PDO::PARAM_STR);
			$consulta->bindValue(":fechaNot", $this->propiedades["fechaNot"], PDO::PARAM_STR);
                        if ($this->propiedades["activa"]) $consulta->bindValue(":activa", true, PDO::PARAM_BOOL);
			else $consulta->bindValue(":activa", false, PDO::PARAM_BOOL);
			$consulta->execute();
			parent::desconectar($conex);
		} catch (PDOException $ex) {
			parent::desconectar($conex);
			errorbd('Error al grabar Noticia en la BD: '.$ex->getMessage(),true);
		}
	}

	public function actualizarEnBD() {

		$conex = parent::conectar();
		$consultaSQL = "UPDATE ".(BD).".".(T_NOTICIAS)." SET
				idUsuario = :idUsuario,
				titular = :titular,
				resumen = :resumen,
				textoNot = :textoNot,
				linkRef = :linkRef,
				fechaNot = :fechaNot,
                                activa = :activa
				WHERE idNoticia = :idNoticia";

		try {   $this->convertirFechas();
			$consulta = $conex->prepare($consultaSQL);
			$consulta->bindValue(":idNoticia", $this->propiedades["idNoticia"], PDO::PARAM_INT);
			$consulta->bindValue(":idUsuario", $this->propiedades["idUsuario"], PDO::PARAM_INT);
			$consulta->bindValue(":titular", $this->propiedades["titular"], PDO::PARAM_STR);
			$consulta->bindValue(":resumen", $this->propiedades["resumen"], PDO::PARAM_STR);
			$consulta->bindValue(":textoNot", $this->propiedades["textoNot"], PDO::PARAM_STR);
			$consulta->bindValue(":linkRef", $this->propiedades["linkRef"], PDO::PARAM_STR);
			$consulta->bindValue(":fechaNot", $this->propiedades["fechaNot"], PDO::PARAM_STR);
                        if ($this->propiedades["activa"]) $consulta->bindValue(":activa", true, PDO::PARAM_BOOL);
			else $consulta->bindValue(":activa", false, PDO::PARAM_BOOL);
			$consulta->execute();
			parent::desconectar($conex);
		} catch (PDOException $ex) {
			parent::desconectar($conex);
			errorbd('Error al actualizar Noticia en la BD: '.$ex->getMessage(), true);
		}
	}

	public function borrarDeBD() {

		$conex = parent::conectar();
		$consultaSQL = "DELETE FROM ".(BD).".".(T_NOTICIAS).
			       " WHERE idNoticia = :idNoticia";

		try {
			$consulta = $conex->prepare($consultaSQL);
			$consulta->bindValue(":idNoticia", $this->propiedades["idNoticia"], PDO::PARAM_INT);
			$consulta->execute();
			parent::desconectar($conex);
		} catch (PDOException $ex) {
			parent::desconectar($conex);
			errorbd('Error al borrar Noticia en la BD: '.$ex->getMessage(), true);
		}
	}
}
?>