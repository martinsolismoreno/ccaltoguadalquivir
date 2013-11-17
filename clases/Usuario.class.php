<?php

require_once(RUTA_ABS."/clases/ObjetoBD.class.php");

class Usuario extends ObjetoBD {

	protected $propiedades = array(
		"idUsuario" => "",
		"usuario" => "",
		"password" => "",
		"email" => "",
		"tipoUsuario" => "",
		"nombre" => "",
		"apellido1" => "",
  	        "apellido2" => "",
       		"edad" => "",
       		"sexo" => "",
		"idSector" => "",
		"fecha" => ""
	);

	static protected $patrones = array(
               "idUsuario" => EXREG_idUsuario,
               "usuario" => EXREG_usuario,
               "password" => EXREG_password,
               "email" => EXREG_email,
               "tipoUsuario" => EXREG_tipoUsuario,
               "nombre" => EXREG_nombre,
               "apellido1" => EXREG_apellido1,
               "apellido2" => EXREG_apellido2,
               "edad" => EXREG_edad,
               "sexo" => EXREG_sexo,
               "idSector" => EXREG_idSector,
               "fecha" => EXREG_fecha
	 );

	protected function valorCorrecto($patron,$valor) {
    		return (array_key_exists($patron, Usuario::$patrones)) ? preg_match(Usuario::$patrones[$patron],$valor):0;
        }


	public static function obtenerPorIdUsuario($idUsuario) {
		$conex = parent::conectar();
		$consultaSQL = "SELECT * FROM ".(BD).".".T_USUARIOS." WHERE idUsuario = :idUsuario";

		try {
			$consulta = $conex->prepare($consultaSQL);
			$consulta->bindValue(":idUsuario", $idUsuario, PDO::PARAM_INT);
			$consulta->execute();
			$registro = $consulta->fetch();
			parent::desconectar($conex);
			if ($registro) return new Usuario($registro);
		} catch (PDOException $ex) {
			parent::desconectar($conex);
			errorbd("Consulta por idUsuario fallida en Usuarios: ".$ex->getMessage(),true);
		}

	}

	public static function obtenerPorUsuario($usuario) {
		$conex = parent::conectar();
		$consultaSQL = "SELECT * FROM ".(BD).".".T_USUARIOS." WHERE usuario = :usuario";
		try {
			$consulta = $conex->prepare($consultaSQL);
			$consulta->bindValue(":usuario", $usuario, PDO::PARAM_INT);
			$consulta->execute();
			$registro = $consulta->fetch();
			parent::desconectar($conex);
			if ($registro) return new Usuario($registro);
		} catch (PDOException $ex) {
			parent::desconectar($conex);
			errorbd("Consulta por usuario fallida en Usuarios: ".$ex->getMessage(),true);
		}

	}


	public static function obtenerPorEmail($email) {
		$conex = parent::conectar();
		$consultaSQL = "SELECT * FROM ".(BD).".".T_USUARIOS." WHERE email = :email";

		try {
			$consulta = $conex->prepare($consultaSQL);
			$consulta->bindValue(":email", $email, PDO::PARAM_INT);
			$consulta->execute();
			$registro = $consulta->fetch();
			parent::desconectar($conex);
			if ($registro) return new Usuario($registro);	
		} catch (PDOException $ex) {
			parent::desconectar($conex);
			errorbd("Consulta por email fallida en Usuarios: ".$ex->getMessage(), true);
		}

	}
	
	public function obtenerNivel() {
		if ($this->propiedades["tipoUsuario"]=="cliente") return NORMAL;
	    elseif ($this->propiedades["tipoUsuario"]=="socio") return NORMAL;
	    elseif ($this->propiedades["tipoUsuario"]=="premium") return PREMIUM;
	    elseif ($this->propiedades["tipoUsuario"]=="gestor") return GESTOR;
	    elseif ($this->propiedades["tipoUsuario"]=="administrador") return ADMON;
              else return ANONIMO; //si el valor en la base de datos está mal o el objeto consultado no tiene este valor definido
	}

	public function autentificar() {
		$conex = parent::conectar();
		$consultaSQL = "SELECT * FROM ".(BD).".".T_USUARIOS.
			       " WHERE usuario = :usuario AND password = password(:password)";

		try {
			$consulta = $conex->prepare($consultaSQL);
			$consulta->bindValue(":usuario", $this->propiedades["usuario"], PDO::PARAM_STR);
			$consulta->bindValue(":password", $this->propiedades["password"], PDO::PARAM_STR);
			$consulta->execute();
			$registro = $consulta->fetch();
			parent::desconectar($conex);
			if ($registro) return new Usuario($registro);	
		} catch (PDOException $ex) {
			parent::desconectar($conex);
			errorbd("Error al intentar identificar usuario: ".$ex->getMessage(),true);
		}

	}


	public function grabarEnBD() {
		
		$conex = parent::conectar();
		$consultaSQL = "INSERT INTO ".(BD).".".(T_USUARIOS)."(
				usuario,
				password,
				email,
				tipoUsuario,
				nombre,
				apellido1,
				apellido2,
				edad,				
				sexo,
				idSector)
				VALUES (
				:usuario,
				password(:password),
				:email,
				:tipoUsuario,
				:nombre,
				:apellido1,
				:apellido2,
				:edad,				
				:sexo,
				:idSector)";

		try {
			$consulta = $conex->prepare($consultaSQL);
			$consulta->bindValue(":usuario", $this->propiedades["usuario"], PDO::PARAM_STR);
			$consulta->bindValue(":password", $this->propiedades["password"], PDO::PARAM_STR);
			$consulta->bindValue(":email", $this->propiedades["email"], PDO::PARAM_STR);
			$consulta->bindValue(":tipoUsuario", $this->propiedades["tipoUsuario"], PDO::PARAM_STR);
			$consulta->bindValue(":nombre", $this->propiedades["nombre"], PDO::PARAM_STR);
			$consulta->bindValue(":apellido1", $this->propiedades["apellido1"], PDO::PARAM_STR);
			$consulta->bindValue(":apellido2", $this->propiedades["apellido2"], PDO::PARAM_STR);
			$consulta->bindValue(":edad", $this->propiedades["edad"], PDO::PARAM_INT);
			$consulta->bindValue(":sexo", $this->propiedades["sexo"], PDO::PARAM_STR);
			$consulta->bindValue(":idSector", $this->propiedades["idSector"], PDO::PARAM_INT);
			$consulta->execute();
			parent::desconectar($conex);
		} catch (PDOException $ex) {
			parent::desconectar($conex);
			errorbd('Error al grabar Usuario en la BD: '.$ex->getMessage(),true);
		}
	}
	
	public function actualizarPassword($clave) {
                if (preg_match(EXREG_password,$clave)) {
      		    $conex = parent::conectar();
      		    $consultaSQL = "UPDATE ".(BD).".".(T_USUARIOS)." SET
      		   		    password = password(:password)
      				    WHERE idUsuario = :idUsuario";
      		    try {
      		        $consulta = $conex->prepare($consultaSQL);
      		        $consulta->bindValue(":idUsuario", $this->propiedades["idUsuario"], PDO::PARAM_INT);
      			$consulta->bindValue(":password", $clave, PDO::PARAM_STR);
      			$consulta->execute();
      			parent::desconectar($conex);
      		    } catch (PDOException $ex) {
      			parent::desconectar($conex);
      			errorbd('Error al activar Oferta en la BD: '.$ex->getMessage(), true);
      		    }
      	       }
	}

	public function actualizarEnBD() {

		$conex = parent::conectar();
		$passwordSQL =  $this->propiedades["password"] ? "password = password(:password), " : "";
		$consultaSQL = "UPDATE ".(BD).".".(T_USUARIOS)." SET
				usuario = :usuario,
				$passwordSQL
				email = :email,
				tipoUsuario = :tipoUsuario,
				nombre = :nombre,
				apellido1 = :apellido1,
				apellido2 = :apellido2,
				edad = :edad,
				sexo = :sexo,
				idSector = :idSector
				WHERE idUsuario = :idUsuario";

		try {
			$consulta = $conex->prepare($consultaSQL);
			$consulta->bindValue(":idUsuario", $this->propiedades["idUsuario"], PDO::PARAM_INT);
                        $consulta->bindValue(":usuario", $this->propiedades["usuario"], PDO::PARAM_STR);
			if ($this->propiedades["password"])
				$consulta->bindValue(":password", $this->propiedades["password"],
							PDO::PARAM_STR);
			$consulta->bindValue(":email", $this->propiedades["email"], PDO::PARAM_STR);
			$consulta->bindValue(":tipoUsuario", $this->propiedades["tipoUsuario"], PDO::PARAM_STR);
			$consulta->bindValue(":nombre", $this->propiedades["nombre"], PDO::PARAM_STR);
			$consulta->bindValue(":apellido1", $this->propiedades["apellido1"], PDO::PARAM_STR);
			$consulta->bindValue(":apellido2", $this->propiedades["apellido2"], PDO::PARAM_STR);
			$consulta->bindValue(":edad", $this->propiedades["edad"], PDO::PARAM_INT);
			$consulta->bindValue(":sexo", $this->propiedades["sexo"], PDO::PARAM_STR);
			$consulta->bindValue(":idSector", $this->propiedades["idSector"], PDO::PARAM_INT);
			$consulta->execute();
			parent::desconectar($conex);
		} catch (PDOException $ex) {
			parent::desconectar($conex);
			errorbd('Error al actualizar Usuario en la BD: '.$ex->getMessage(), true);
		}
	}

	public function borrarDeBD() {

		$conex = parent::conectar();
		$consultaSQL = "DELETE FROM ".(BD).".".(T_USUARIOS).
			       " WHERE idUsuario = :idUsuario";

		try {
			$consulta = $conex->prepare($consultaSQL);
			$consulta->bindValue(":idUsuario", $this->propiedades["idUsuario"], PDO::PARAM_INT);
			$consulta->execute();
			parent::desconectar($conex);
		} catch (PDOException $ex) {
			parent::desconectar($conex);
			errorbd('Error al borrar Usuario en la BD: '.$ex->getMessage(), true);
		}
	}
}
?>