<?php

require_once(RUTA_ABS."/clases/ObjetoBD.class.php");

class Empresa extends ObjetoBD {

	protected $propiedades = array(
		"idEmpresa" => "",
		"idUsuario" => "",
    	        "dni" => "",
		"empresa" => "",
	 	"descripcion" => "",
        	"direccion" => "",
         	"pedania" => "",
         	"idPoblacion" => "",
         	"telefono1" => "",
         	"telefono2" => "",
         	"fax" => "",
         	"email" => "",
         	"web" => "",
         	"horario" => "",
		"idSector" => "",
		"validada" => "",
		"fecha" => ""
	);

	static protected $patrones = array(

               	"idEmpresa" => EXREG_idEmpresa,
		"idUsuario" => EXREG_idUsuario,
    	        "dni" =>  EXREG_dni,
		"empresa" => EXREG_empresa,
	 	"descripcion" => EXREG_descripcion,
        	"direccion" => EXREG_direccion,
                "pedania" => EXREG_pedania,
         	"idPoblacion" => EXREG_idPoblacion,
         	"telefono1" => EXREG_telefono1,
         	"telefono2" => EXREG_telefono2,
         	"fax" => EXREG_fax,
         	"email" => EXREG_email,
         	"web" => EXREG_web,
         	"horario" => EXREG_horario,
		"idSector" => EXREG_idSector,
		"validada" => EXREG_validada,
		"fecha" => EXREG_fecha

	 );

	protected function valorCorrecto($patron,$valor) {
    		return (array_key_exists($patron, Empresa::$patrones)) ? preg_match(Empresa::$patrones[$patron],$valor):0;
        }


        public static function obtenerPorIdEmpresa($idEmpresa) {
		$conex = parent::conectar();
		$consultaSQL = "SELECT * FROM ".(BD).".".T_EMPRESAS." WHERE idEmpresa = :idEmpresa";

		try {
			$consulta = $conex->prepare($consultaSQL);
			$consulta->bindValue(":idEmpresa", $idEmpresa, PDO::PARAM_INT);
			$consulta->execute();
			$registro = $consulta->fetch();
			parent::desconectar($conex);
			if ($registro) return new Empresa($registro);
		} catch (PDOException $ex) {
			parent::desconectar($conex);
			errorbd("Consulta por idEmpresa fallida en Empresas: ".$ex->getMessage(),true);
		}

	}


        public static function obtenerPorIdUsuario($idUsuario) {
		$conex = parent::conectar();
		$consultaSQL = "SELECT * FROM ".(BD).".".T_EMPRESAS." WHERE idUsuario = :idUsuario";

		try {
			$consulta = $conex->prepare($consultaSQL);
			$consulta->bindValue(":idUsuario", $idUsuario, PDO::PARAM_INT);
			$consulta->execute();
			$registro = $consulta->fetch();
			parent::desconectar($conex);
			if ($registro) return new Empresa($registro);
		} catch (PDOException $ex) {
			parent::desconectar($conex);
			errorbd("Consulta por idUsuario fallida en Empresas: ".$ex->getMessage(),true);
		}

	}

        public static function obtenerPorDNI($dni) {
		$conex = parent::conectar();
		$consultaSQL = "SELECT * FROM ".(BD).".".T_EMPRESAS." WHERE dni = :dni";

		try {
			$consulta = $conex->prepare($consultaSQL);
			$consulta->bindValue(":dni", $dni, PDO::PARAM_INT);
			$consulta->execute();
			$registro = $consulta->fetch();
			parent::desconectar($conex);
			if ($registro) return new Empresa($registro);
		} catch (PDOException $ex) {
			parent::desconectar($conex);
			errorbd("Consulta por DNI fallida en Empresas: ".$ex->getMessage(),true);
		}

	}
	
        public static function empresaValidada($idEmpresa) {
		$conex = parent::conectar();
		$consultaSQL = "SELECT validada FROM ".(BD).".".T_EMPRESAS." WHERE idEmpresa = :idEmpresa";

		try {
			$consulta = $conex->prepare($consultaSQL);
			$consulta->bindValue(":idEmpresa", $idEmpresa, PDO::PARAM_INT);
			$consulta->execute();
			$registro = $consulta->fetch();
			parent::desconectar($conex);
			return $registro["validada"];
		} catch (PDOException $ex) {
			parent::desconectar($conex);
			errorbd("Consulta por idEmpresa fallida en Empresas: ".$ex->getMessage(),true);
		}

	}
	
        public static function hayEmpresasPdtesValidar() {
		$conex = parent::conectar();
		$consultaSQL = "SELECT count(*) FROM ".(BD).".".T_EMPRESAS." WHERE validada = false";

		try {
			$consulta = $conex->prepare($consultaSQL);
			$consulta->execute();
			$registro = $consulta->fetch();
			parent::desconectar($conex);
			return $registro[0];
		} catch (PDOException $ex) {
			parent::desconectar($conex);
			errorbd("Consulta por idEmpresa fallida en Empresas: ".$ex->getMessage(),true);
		}

	}

	public function grabarEnBD() {

		$conex = parent::conectar();
		$consultaSQL = "INSERT INTO ".(BD).".".(T_EMPRESAS)."(
				idUsuario,
				dni,
				empresa,
				descripcion,
				direccion,
				pedania,
				idPoblacion,
				telefono1,
				telefono2,
				fax,
				email,
				web,
				horario,
				idSector,
                                validada)
				VALUES (
				:idUsuario,
				:dni,
				:empresa,
				:descripcion,
				:direccion,
				:pedania,
				:idPoblacion,
				:telefono1,
				:telefono2,
				:fax,
				:email,
				:web,
				:horario,
				:idSector,
                                :validada)";
		try {
			$consulta = $conex->prepare($consultaSQL);
			$consulta->bindValue(":idUsuario", $this->propiedades["idUsuario"], PDO::PARAM_STR);
			$consulta->bindValue(":dni", $this->propiedades["dni"], PDO::PARAM_STR);
			$consulta->bindValue(":empresa", $this->propiedades["empresa"], PDO::PARAM_STR);
			$consulta->bindValue(":descripcion", $this->propiedades["descripcion"], PDO::PARAM_STR);
			$consulta->bindValue(":direccion", $this->propiedades["direccion"], PDO::PARAM_STR);
			$consulta->bindValue(":pedania", $this->propiedades["pedania"], PDO::PARAM_STR);
			$consulta->bindValue(":idPoblacion", $this->propiedades["idPoblacion"], PDO::PARAM_INT);
			$consulta->bindValue(":telefono1", $this->propiedades["telefono1"], PDO::PARAM_STR);
			$consulta->bindValue(":telefono2", $this->propiedades["telefono2"], PDO::PARAM_STR);
			$consulta->bindValue(":fax", $this->propiedades["fax"], PDO::PARAM_STR);
			$consulta->bindValue(":email", $this->propiedades["email"], PDO::PARAM_STR);
			$consulta->bindValue(":web", $this->propiedades["web"], PDO::PARAM_STR);
			$consulta->bindValue(":horario", $this->propiedades["horario"], PDO::PARAM_STR);
			$consulta->bindValue(":idSector", $this->propiedades["idSector"], PDO::PARAM_INT);
			$consulta->bindValue(":validada", false, PDO::PARAM_BOOL); //siempre en el alta está invalidada
			$consulta->execute();
			parent::desconectar($conex);
		} catch (PDOException $ex) {
			parent::desconectar($conex);
			errorbd('Error al grabar Empresa en la BD: '.$ex->getMessage(),true);
		}
	}
	
        public function actualizarIdUsuario($idUsuario) {
               $this->propiedades["idUsuario"]=$idUsuario;
	}
	
        public function darPorValida() {
               $this->propiedades["validada"]=1;
	}

        public function validarEmpresa() {
		$conex = parent::conectar();
		$consultaSQL = "UPDATE ".(BD).".".(T_EMPRESAS)." SET
				validada = true
				WHERE idEmpresa = :idEmpresa";
		try {
			$consulta = $conex->prepare($consultaSQL);
			$consulta->bindValue(":idEmpresa", $this->propiedades["idEmpresa"], PDO::PARAM_INT);
			$consulta->execute();
			parent::desconectar($conex);
		} catch (PDOException $ex) {
			parent::desconectar($conex);
			errorbd('Error al validar Empresa en la BD: '.$ex->getMessage(), true);
		}
	}


	public function actualizarEnBD() {

		$conex = parent::conectar();
		$consultaSQL = "UPDATE ".(BD).".".(T_EMPRESAS)." SET
				idUsuario = :idUsuario,
				dni = :dni,
				empresa = :empresa,
				descripcion = :descripcion,
				direccion = :direccion,
				pedania = :pedania,
				idPoblacion = :idPoblacion,
				telefono1 = :telefono1,
				telefono2 = :telefono2,
				fax = :fax,
				email = :email,
				web = :web,
				horario = :horario,
				idSector = :idSector,
				validada = :validada
				WHERE idEmpresa = :idEmpresa";

		try {
			$consulta = $conex->prepare($consultaSQL);
			$consulta->bindValue(":idEmpresa", $this->propiedades["idEmpresa"], PDO::PARAM_INT);
			$consulta->bindValue(":idUsuario", $this->propiedades["idUsuario"], PDO::PARAM_STR);
			$consulta->bindValue(":dni", $this->propiedades["dni"], PDO::PARAM_STR);
                        $consulta->bindValue(":empresa", $this->propiedades["empresa"], PDO::PARAM_STR);
			$consulta->bindValue(":descripcion", $this->propiedades["descripcion"], PDO::PARAM_STR);
			$consulta->bindValue(":direccion", $this->propiedades["direccion"], PDO::PARAM_STR);
			$consulta->bindValue(":pedania", $this->propiedades["pedania"], PDO::PARAM_STR);
			$consulta->bindValue(":idPoblacion", $this->propiedades["idPoblacion"], PDO::PARAM_INT);
			$consulta->bindValue(":telefono1", $this->propiedades["telefono1"], PDO::PARAM_STR);
			$consulta->bindValue(":telefono2", $this->propiedades["telefono2"], PDO::PARAM_STR);
			$consulta->bindValue(":fax", $this->propiedades["fax"], PDO::PARAM_STR);
			$consulta->bindValue(":email", $this->propiedades["email"], PDO::PARAM_STR);
			$consulta->bindValue(":web", $this->propiedades["web"], PDO::PARAM_STR);
			$consulta->bindValue(":horario", $this->propiedades["horario"], PDO::PARAM_STR);
			$consulta->bindValue(":idSector", $this->propiedades["idSector"], PDO::PARAM_INT);
                        if ($this->propiedades["validada"]) $consulta->bindValue(":validada", true, PDO::PARAM_BOOL);
			else $consulta->bindValue(":validada", false, PDO::PARAM_BOOL);
			$consulta->execute();
			parent::desconectar($conex);
		} catch (PDOException $ex) {
			parent::desconectar($conex);
			errorbd('Error al actualizar Empresa en la BD: '.$ex->getMessage(), true);
		}
	}

	public function borrarDeBD() {

		$conex = parent::conectar();
		$consultaSQL = "DELETE FROM ".(BD).".".(T_EMPRESAS).
			       " WHERE idEmpresa = :idEmpresa";

		try {
			$consulta = $conex->prepare($consultaSQL);
			$consulta->bindValue(":idEmpresa", $this->propiedades["idEmpresa"], PDO::PARAM_INT);
			$consulta->execute();
			parent::desconectar($conex);
		} catch (PDOException $ex) {
			parent::desconectar($conex);
			errorbd('Error al borrar Empresa en la BD: '.$ex->getMessage(), true);
		}
	}
}
?>