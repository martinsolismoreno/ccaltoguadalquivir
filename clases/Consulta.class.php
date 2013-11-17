<?php

require_once(RUTA_ABS."/clases/ObjetoBD.class.php");

class Consulta extends ObjetoBD {

	protected $propiedades = array(
		"consulta" => "",
		"orden"    => "",
		"objeto"   => "",
		"tamPag"   => ""
	);


	protected $resultados = array(
		"pagAct"   => "",
		"totPag"    => "",
		"totReg"    => "",
		"datos"    => ""
	);

//////////////////////////////////////////////////////////

       	static protected $patrones = array(
               "consulta" => EXREG_consulta,
               "orden"    => EXREG_orden,
               "objeto"   => EXREG_objeto,
               "tamPag"   => EXREG_tamPag
	 );

        protected function valorCorrecto($patron,$valor) {
       		return (array_key_exists($patron, Consulta::$patrones)) ? preg_match(Consulta::$patrones[$patron],$valor):0;
        }
        
/////////////////////////////////////////////////////////

        public static function obtenerConsultaUsuarios($ordenacion="", $numRegistros="1") {

                $ordenacion = ($ordenacion) ? $ordenacion : array(array("orden"=>"usuario", "sentido"=>"asc"));
                $cadOrdenacion = crearCadOrdenacion($ordenacion);
		return new Consulta( array (
		           "consulta" => "SELECT * FROM ".(BD).".".(T_USUARIOS),
		           "orden" => $cadOrdenacion,
			   "objeto" => "Usuario",
			   "tamPag" =>(int)$numRegistros));

	}
	

	public static function obtenerConsultaEmpresas($ordenacion="", $numRegistros="1", $todas="si") {

                $whereValidada = ($todas=="no") ? " A.validada = 0 AND " : "";
                $ordenacion = ($ordenacion) ? $ordenacion : array(array("orden"=>"nombre", "sentido"=>"asc"));
                $cadOrdenacion = crearCadOrdenacion($ordenacion);
                $consulta = "SELECT A.idUsuario, A.empresa, B.poblacion, C.sector, D.usuario ".
                                    "FROM ".(BD)."." .(T_EMPRESAS)." A, ".
                                            (BD)."." .(T_POBLACIONES)." B, ".
                                            (BD)."." .(T_SECTORES)." C, ".
                                            (BD)."." .(T_USUARIOS)." D ".
                                    "WHERE $whereValidada A.idPoblacion = B.idPoblacion AND A.idSector = C.idSector AND A.idUsuario = D.idUsuario ";
 		return  new Consulta( array (
     		             "consulta" => $consulta,
     		             "orden"    => $cadOrdenacion,
        		     "objeto"   => "EmpPoblSecUsu",
       		             "tamPag"   =>(int)$numRegistros));

	}

	public static function obtenerConsultaAccesos($idUsuario="", $ordenacion="", $numRegistros="1") {

                $cadWhere = ($idUsuario) ? " WHERE idUsuario = '".$idUsuario."' " : "";
                $ordenacion = ($ordenacion) ? $ordenacion : array(array("orden"=>"ultAcceso", "sentido"=>"desc"));
                $cadOrdenacion = crearCadOrdenacion($ordenacion);
                if ($cadWhere)
      		      return  new Consulta( array (
      		           "consulta" => "SELECT * FROM ".(BD)."." .(T_ACCESOS)." $cadWhere ",
      		           "orden"    => $cadOrdenacion,
      			   "objeto"   => "Acceso",
      			   "tamPag"   => (int)$numRegistros));
      		else
        	      return  new Consulta( array (
      		           "consulta" => "SELECT A.idUsuario, B.usuario, A.pagina, A.numVisitas, A.ultAcceso ".
                                         "FROM ".(BD)."." .(T_ACCESOS)." A, ".(BD)."." .(T_USUARIOS)." B ".
                                         "WHERE A.idUsuario = B.idUsuario ",
                           "orden"    => $cadOrdenacion,
      			   "objeto"   => "UsuarioAcceso",
     			   "tamPag"   => (int)$numRegistros));

	}         


	public static function obtenerConsultaOfertas($idEmpresa="", $ordenacion="", $numRegistros="1") {

                $cadWhere = ($idEmpresa) ? " WHERE idEmpresa = '".$idEmpresa."' " : "";
                $ordenacion = ($ordenacion) ? $ordenacion : array(array("orden"=>"oferta", "sentido"=>"asc"));
                $cadOrdenacion = crearCadOrdenacion($ordenacion);
                if ($cadWhere)
      		      return  new Consulta( array (
      		           "consulta" => "SELECT * FROM ".(BD)."." .(T_OFERTAS)." $cadWhere ",
      		           "orden"    => $cadOrdenacion,
      			   "objeto"   => "Oferta",
      			   "tamPag"   => (int)$numRegistros));
      		else
        	      return new Consulta( array (
      		           "consulta" => "SELECT A.idOferta, A.idEmpresa, B.idUsuario, A.oferta, B.empresa, A.fechaIni, A.fechaFin, A.activa ".
                                         "FROM ".(BD)."." .(T_OFERTAS)." A, ".(BD)."." .(T_EMPRESAS)." B ".
                                         "WHERE A.idEmpresa = B.idEmpresa ",
                           "orden"    => $cadOrdenacion,
      			   "objeto"   => "OfertaEmpresa",
     			   "tamPag"   => (int)$numRegistros));

	}


	public static function obtenerConsultaNoticias($idUsuario="", $ordenacion="", $numRegistros="1") {

                $cadWhere = ($idUsuario) ? " WHERE idUsuario = '".$idUsuario."' " : "";
                $ordenacion = ($ordenacion) ? $ordenacion : array(array("orden"=>"oferta", "sentido"=>"asc"));
                $cadOrdenacion = crearCadOrdenacion($ordenacion);
                if ($cadWhere)
      		      return  new Consulta( array (
      		           "consulta" => "SELECT * FROM ".(BD)."." .(T_NOTICIAS)." $cadWhere ",
      		           "orden"    => $cadOrdenacion,
      			   "objeto"   => "Noticia",
      			   "tamPag"   => (int)$numRegistros));
      		else
        	      return new Consulta( array (
      		           "consulta" => "SELECT A.idNoticia, A.idUsuario, A.titular, B.usuario, A.fechaNot, A.activa ".
                                         "FROM ".(BD)."." .(T_NOTICIAS)." A, ".(BD)."." .(T_USUARIOS)." B ".
                                         "WHERE A.idUsuario = B.idUsuario ",
                           "orden"    => $cadOrdenacion,
      			   "objeto"   => "NoticiaUsuario",
     			   "tamPag"   => (int)$numRegistros));

	}

	public static function obtenerOfertasPagPpal() {
                $fechaHoy = date("Y")."-".date("m")."-".date("d");
                $cadWhere = " WHERE activa = true AND fichero = true AND fechaIni != '0000-00-00' AND fechaIni <= '$fechaHoy' ";
                $ordenacion = array (array("orden"=>"fechaFin", "sentido"=>"asc"), array("orden"=>"fechaIni", "sentido"=>"asc"));
                $cadOrdenacion = crearCadOrdenacion($ordenacion);
                return  new Consulta( array (
      		           "consulta" => "SELECT * FROM ".(BD)."." .(T_OFERTAS)." ".$cadWhere,
      		           "orden"    => $cadOrdenacion,
      			   "objeto"   => "Oferta",
      			   "tamPag"   => 1
      		           ));
	}


	public static function obtenerNoticias() {
                $cadWhere = " WHERE activa = true ";
                $ordenacion = array (array("orden"=>"fechaNot", "sentido"=>"desc"), array("orden"=>"titular", "sentido"=>"asc"));
                $cadOrdenacion = crearCadOrdenacion($ordenacion);
                return  new Consulta( array (
      		           "consulta" => "SELECT * FROM ".(BD)."." .(T_NOTICIAS)." ".$cadWhere,
      		           "orden"    => $cadOrdenacion,
      			   "objeto"   => "Noticia",
      			   "tamPag"   => 1
      		           ));
	}

	public static function obtenerEmpresas($idSector=0,$idPoblacion=0){
                $cadWhere = " WHERE idEmpresa > 1 AND validada = true ";
                $cadWhere.= ($idSector) ? " AND idSector = $idSector " : "";
                $cadWhere.= ($idPoblacion) ? " AND idPoblacion = $idPoblacion " : "";
                $ordenacion = array (array("orden"=>"idSector", "sentido"=>"asc"), array("orden"=>"idPoblacion", "sentido"=>"asc"), array("orden"=>"empresa", "sentido"=>"asc"));
                $cadOrdenacion = crearCadOrdenacion($ordenacion);
                return  new Consulta( array (
      		           "consulta" => "SELECT * FROM ".(BD)."." .(T_EMPRESAS)." ".$cadWhere,
      		           "orden"    => $cadOrdenacion,
      			   "objeto"   => "Empresa",
      			   "tamPag"   => 1
      		           ));
	}


	public static function obtenerOfertas($idEmpresa=0,$idSector=0,$idPoblacion=0){
                $fechaHoy = date("Y")."-".date("m")."-".date("d");
                $cadWhere = " WHERE ";
                $cadWhere.= ($idEmpresa) ? " A.idEmpresa = $idEmpresa AND " : "";
                $cadWhere.= " A.idEmpresa = B.idEmpresa AND A.validada = true AND B.activa = true AND B.fechaIni != '0000-00-00' AND B.fechaIni <= '$fechaHoy' ";
                $cadWhere.= ((!$idEmpresa)&&($idSector)) ? " AND A.idSector = $idSector " : "";
                $cadWhere.= ((!$idEmpresa)&&($idPoblacion)) ? " AND A.idPoblacion = $idPoblacion " : "";
                $ordenacion = array (array("orden"=>"B.fechaFin", "sentido"=>"asc"), array("orden"=>"B.fechaIni", "sentido"=>"asc"), array("orden"=>"B.oferta", "sentido"=>"asc"));
                $cadOrdenacion = crearCadOrdenacion($ordenacion);
                return  new Consulta( array (
      		           "consulta" => "SELECT B.* FROM ".(BD)."." .(T_EMPRESAS)." A, ".(BD)."." .(T_OFERTAS)." B ".$cadWhere,
      		           "orden"    => $cadOrdenacion,
      			   "objeto"   => "Oferta",
      			   "tamPag"   => 1
      		           ));
	}


/////////////////////////////////////////////////////////


	private function cargarDatos() {

		$conex = parent::conectar();

		if (($this->propiedades["tamPag"]>0) AND ($this->propiedades["consulta"] !="") AND ($this->propiedades["objeto"] !="")) {
                        $consultaSQL = preg_replace("/^(SELECT)/","SELECT SQL_CALC_FOUND_ROWS ",$this->propiedades["consulta"]).
                                       " ".$this->propiedades["orden"]." LIMIT :posicion, :tamPag";

			$this->resultados["pagAct"] = ($this->resultados["pagAct"]) ? $this->resultados["pagAct"] : 1;
			try {
				$consulta = $conex->prepare($consultaSQL);
				$consulta->bindValue(":posicion", ($this->resultados["pagAct"]-1)*$this->propiedades["tamPag"], PDO::PARAM_INT);
				$consulta->bindValue(":tamPag", $this->propiedades["tamPag"], PDO::PARAM_INT);
				$consulta->execute();
				$registros = array();
				foreach ($consulta->fetchAll() as $registro) {
					$objeto = new $this->propiedades["objeto"]($registro);
					$registros[] = $objeto->arrayPropiedades();
				}
				$this->resultados["datos"]=$registros;
				$consulta = $conex->query("SELECT found_rows() AS totalRegistros");
				$registro = $consulta->fetch();
				$this->resultados["totReg"]=$registro["totalRegistros"];
				$res = explode(".",$this->resultados["totReg"]/$this->propiedades["tamPag"]);
				$this->resultados["totPag"] = $res[0]+count($res)-1;
				$this->resultados["pagAct"] = ($this->resultados["totReg"]) ? $this->resultados["pagAct"] : 0;
				parent::desconectar($conex);
			} catch (PDOException $ex) {
				parent::desconectar($conex);
				errorbd("Consulta de objetos ".$this->propiedades["objeto"]." fallida: ".$ex->getMessage(),true);
			}
		 } else {
		 	$this->resultados["datos"] = array();
		 	$this->resultados["totReg"] = 0;
		 	$this->resultados["totPag"] = 0;
		 	$this->resultados["pagAct"] = 0;
		 }
	}
	


	public function modTamPag($tamPag=10) {
      	        if (preg_match("/[^0-9]/",$tamPag)) //si viene algo no numérico, devolvemos lo que hay, la pagina actual y sin actualizar;
      	            return $this->resultados["datos"];
      	        $tamPag = ($tamPag>99) ? 99: $tamPag;
                $posInicioPag = ($this->resultados["pagAct"]=="") ? 0 : (($this->resultados["pagAct"]-1)*$this->propiedades["tamPag"])+1;
                $res = explode(".",$posInicioPag / $tamPag);
                $nuevaPagAct = $res[0]+count($res)-1;
		$this->propiedades["tamPag"]=(int)$tamPag;
		return $this->irPag($nuevaPagAct);
	}

	public function pagAct() {
		if ($this->resultados["pagAct"]=="") $this->cargarDatos();
		return $this->resultados["pagAct"];
	}

	public function totPag() {
		if ($this->resultados["totPag"]=="") $this->cargarDatos();
		return $this->resultados["totPag"];
	}

	public function totReg() {
		if ($this->resultados["totReg"]=="") $this->cargarDatos();
		return $this->resultados["totReg"];
	}

      	public function posIniPag() {
		$pagAct = $this->pagAct();
		return ($pagAct) ? (($pagAct-1)*$this->propiedades["tamPag"])+1 : 0;
	}

      	public function posFinPag() {
		$pagAct = $this->pagAct();
		$pos = ($pagAct) ? (($pagAct-1)*$this->propiedades["tamPag"])+$this->propiedades["tamPag"] : 0;
		return ($pos > $this->resultados["totReg"]) ? $this->resultados["totReg"] : $pos;
	}

        public function recuperarPag() {
		return $this->resultados["datos"];
	}

        public function actualizarPag() {
		$this->cargarDatos();
		return $this->resultados["datos"];
	}

	public function irPag($numPag=1) {
		if (preg_match("/[^0-9]/",$numPag)) //si viene algo no numérico, devolvemos lo que hay, la pagina actual y sin actualizar;
		    return $this->resultados["datos"];

		if ((!$numPag)OR($numPag<1)) { //si $num < 1 nos vamos a la inicial
		    return $this->pagInicial();
 		} else { //  En caso contrario, entraremos en bucle hasta tener la pagina que queremos o la final si hay menos de la pedida, por si hay cambios dinámicos
		    do {
		    	if ($numPag > $this->resultados["totPag"]) $this->resultados["pagAct"] = $this->resultados["totPag"];
                                                             else  $this->resultados["pagAct"] = $numPag;
		    	$this->cargarDatos();
                    } while ( ((($numPag <= $this->resultados["totPag"]) AND ($this->resultados["pagAct"] != $numPag)) OR //...si hay más paginas que la pedida y en el nuevo rango no es la nuestra
		    	       (($numPag > $this->resultados["totPag"] ) AND ($this->resultados["pagAct"] != $this->resultados["totPag"]))) AND //o hay más, pero no estamos en la última página aún porque han cambiado
			        ($this->resultados["totPag"] > 0) );  //y hay paginas, porque si no hay, salimos a cero
		    return $this->resultados["datos"];
 		}
	}

	public function pagInicial() {
		$this->resultados["pagAct"] = 1;
		$this->cargarDatos();
		return $this->resultados["datos"];
	}

	public function pagFinal() {
	//entramos en bucle hasta que la página actual sea igual a la última por si hay cambios dinámicos en la BD.
		do {
   	     	    $this->resultados["pagAct"] = $this->resultados["totPag"];
       		    $this->cargarDatos();
                } while (($this->resultados["pagAct"] != $this->resultados["totPag"]) AND
		         ($this->resultados["totPag"] > 0) ); //y hay paginas, porque si no hay, salimos a cero
		return $this->resultados["datos"];
        }


	public function pagAnterior() {
		return($this->irPag(($this->resultados["pagAct"])-1));
	}

	public function pagSiguiente() {
		return($this->irPag(($this->resultados["pagAct"])+1));
	}

}
?>