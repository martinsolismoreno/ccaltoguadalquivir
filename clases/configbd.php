<?php

//definiciones de la base de datos

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// definición para el servidor del netbook
define("BD","comercial");
define("DB_DSN", "mysql:host=localhost;dbname=".BD);
define("DB_USUARIO", "martin");
define("DB_PASSWORD", "1234");

// definición para el servidor anónimo
//define("BD","fees0_11963213_comercial");
//define("DB_DSN", "mysql:host=sql313.byetcluster.com:3306:dbname=".BD);
//define("DB_DSN", "mysql:dbname=".BD);
//define("DB_DSN", 'mysql:dbname='.BD.';host=sql313.0fees.net');
//define("DB_USUARIO", '"'."'fees0_11963213'@'192.168.0.2'".'"');
// 	52941 	fees0_11963213 	192.168.0.2:48784 	Ninguna 	Query 	0 	--- 	SHOW PROCESSLIST
//define("DB_USUARIO","fees0_11963213");
//define("DB_PASSWORD", "alfple75");

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//definición de los distintos objetos primarios con correspondencia en tabla de BD
define("USUARIO", "Usuario");
define("EMPRESA", "Empresa");
define("OFERTA", "Oferta");
define("NOTICIA", "Noticia");
define("SECTOR", "Sector");
define("POBLACION", "Poblacion");
define("ACCESO", "Acceso");
//objeto "virtual" fruto de realizar consultas sobro objetos anteriores
//y mezcla de ellos. tiene funciones de registro/pagina siguiente, anterior, etc...
define("CONSULTA", "Consulta");
//mezcla de objetos anteriores usados para el objeto CONSULTA.
define("USUACC","UsuarioAcceso");
define("EMPPOBSECUSU","EmpPoblSecUsu");
define("OFEEMP","OfertaEmpresa");
define("NOTUSU","NoticiaUsuario");

//tablas que albergan los objetos primarios usados
define("T_USUARIOS", "usuarios");
define("T_EMPRESAS", "empresas");
define("T_OFERTAS", "ofertas");
define("T_NOTICIAS", "noticias");
define("T_SECTORES", "sectores");
define("T_POBLACIONES", "poblaciones");
define("T_ACCESOS", "accesos");

//prefijos de los diferentes objetos primarios que se manejan
define("USU","Usuario_");
define("EMP","Empresa_");
define("OFE","Oferta_");
define("NOT","Noticia_");
define("SEC","Sector_");
define("POB","Poblacion_");
define("ACC","Acceso_");

//definición de los distintos niveles de acceso
define("ANONIMO",1); //no conectado
define("NORMAL",2);  //clientes y socios sin privilegios
define("PREMIUM",3); //socios que tiene privilegios (por ahora subir ofertas)
define("GESTOR",4);  //socios con un nivel superior, entre PREMIUM y lo que es ser ADMINISTRADOR
                   //ahora mismo pueden visualizar datos de usuarios, ver listados, modificar el tipo de usuario (de NORMAL, A PREMIUM, A GESTOR)
                   //y pueden subir noticias
define("ADMON",5); //nivel de administración, tiene todos los privilegios


//definición de distintos modos de actuación sobre los objetos y bd

define("VISU",1); //sólo visualización de los datos
define("ALTA",2); //alta = registro
define("MDTU",3); //solo se permite modificar algún campo, el resto visualizar
define("MODI",4); //permite modificar los datos
define("BAJA",5); //es un modo especial, con MODI se puede dar de baja, se usa este estado para pedir confirmación de la baja


//definición de los distintos tipos de usuario que se manejan
define("T_CLIENTE","cliente");
define("T_SOCIO","socio");
define("T_PREMIUM","premium");
define("T_GESTOR","gestor");
define("T_ADMINISTRADOR","administrador");


//// EXPRESIONES REGULARES

//conjuntos de caracteres admitidos en algunos tipos de campos
define("NUMEROS","([0-9])");
define("ALFANUMERICOS","([a-zA-Z0-9])");
define("ALFANUMERICOS_PUNTO_GUION_SUBRAYADO","([a-zA-Z0-9\.\-\_])");
define("ALFANUMERICOS_NOMBRE","([a-zA-Z0-9áéíóúüÁÉÍÓÚÜñÑçÇ\º\ª\s\-\.])");
define("ALFANUMERICOS_NOMBRE_EXT","([\s\-a-zA-Z0-9áéíóúüÁÉÍÓÚÜñÑçÇ\ª\º\'\"\.\,\;\:\(\)\/])"); //DIRECCIONES, POBLACION, PEDANIA, HORARIO...
define("ALFANUMERICOS_NOMBRE_EXT2","([\s\-a-zA-Z0-9áéíóúüÁÉÍÓÚÜñÑçÇ\ª\º\¿\?\!\¡\'\"\@\&\%\.\,\;\:\(\)\*\\\\])"); //NOMBRE DE LA EMPRESA, DESCRIPCION...
define("SELECT",   "([\s\-a-zA-Z0-9\_\?\%\=\!\<\>\&\|\,\;\:\(\)\.\'\"\*\s])");

//expresiones regulares de algunos tipos de campos
define("OBJETO","/(^".USUARIO."$)|(^".EMPRESA."$)|(^".OFERTA."$)|(^".NOTICIA."$)|(^".SECTOR."$)|(^".POBLACION."$)|(^".ACCESO."$)|(^".EMPPOBSECUSU."$)|(^".USUACC."$)|(^".OFEEMP."$)|(^".NOTUSU."$)/");
define("TIPO_USUARIO","/(^".T_CLIENTE."$)|(^".T_SOCIO."$)|(^".T_PREMIUM."$)|(^".T_GESTOR."$)|(^".T_ADMINISTRADOR."$)/");
define("EDAD","/(^(1){1}([8-9]){1}$)|(^([2-9]){1}([0-9]){1}$)/");
define("SEXO","/(^H$)|(^M$)/");
define("DNI","/^\d{8}[TRWAGMYFPDXBNJZSQVHLCKEtrwagmyfpdxbnjzsqvhlcke]{1}$/");
define("TELEFONO","/^[\(]?[6|9]\d{2}[\)]?[\-\s]?\d{3}[\-\s]?\d{3}(\s)*$/");
define("EMAIL","/^[a-zA-Z]{1,}(([_]*[a-zA-Z0-9]{1,})*|([-]*[a-zA-Z0-9]{1,})*|([.]*[a-zA-Z0-9]{1,})*)@[a-zA-Z]{1,}((([-]*[a-zA-Z0-9]{1,}|[a-zA-Z0-9]*))+[.])+[a-zA-Z]{2,4}$/");
define("WEB","/^(http[s]?:\/\/){1}(www\.){1}([\-\_a-zA-Z0-9]+\.)+[a-zA-Z0-9]{2,4}$/");
define("FECHA_HORA","/^([0-9]){4}(\-)([0-9]){2}(\-)([0-9]){2}(\s)([0-9]){2}(\:)([0-9]){2}(\:)([0-9]){2}$/");
define("FECHA","/^([0-9]){2}(\/)([0-9]){2}(\/)([0-9]){4}$/");
define("FECHABD","/^([0-9]){4}(\-)([0-9]){2}(\-)([0-9]){2}$/");

define("LETRAS_DNI","TRWAGMYFPDXBNJZSQVHLCKE");


///// DEFINICION PROPIEDADES CAMPOS

//con estas variables globlables, se controla que al dar de alta cualquier objeto, cumple con las condiciones
//expresadas en el array $camposBD para cualquier campo de las propiedades de ese objeto, que se corresponden con
//campos de la BD. Impido meter información errónea o maligna, pues si no se cumple, no se crea la propiedad

//array global con las propiedades de los diferentes campos que forman las propiedades de los tipos de datos manejados
global $camposBD;

$camposBD = array (


//objeto usuario
"idUsuario" => array ("lmin" => 1, "lmax" => 9, "exreg" => "/^".NUMEROS."{1,9}$/" ),
"usuario" => array ( "lmin" => 1, "lmax" => 30, "exreg" => "/^".ALFANUMERICOS_PUNTO_GUION_SUBRAYADO."{1,30}$/" ),
"password" => array ( "lmin" => 6, "lmax" => 20, "exreg" => "/^".ALFANUMERICOS."{6,20}$/" ),
"email" => array ( "lmin" => 1, "lmax" => 50, "exreg" => EMAIL ),
"tipoUsuario" => array ( "lmin" => 5, "lmax" => 13, "exreg" => TIPO_USUARIO ),
"nombre" => array ( "lmin" => 1, "lmax" => 30, "exreg" => "/^".ALFANUMERICOS_NOMBRE."{1,30}$/" ),
"apellido1" => array ( "lmin" => 1, "lmax" => 30, "exreg" => "/^".ALFANUMERICOS_NOMBRE."{1,30}$/" ),
"apellido2" => array ( "lmin" => 1, "lmax" => 30, "exreg" => "/^".ALFANUMERICOS_NOMBRE."{1,30}$/" ),
"edad" => array ( "lmin" => 2, "lmax" => 2, "exreg" => EDAD ),
"sexo" => array ( "lmin" => 1, "lmax" => 1, "exreg" => SEXO ),
"idSector" => array ( "lmin" => 1, "lmax" => 3, "exreg" => "/^".NUMEROS."{1,3}$/" ),
"fecha" => array ( "lmin" => 1, "lmax" => 19, "exreg" => FECHA_HORA ),

//objeto empresa
"idEmpresa" => array ("lmin" => 1, "lmax" => 9, "exreg" => "/^".NUMEROS."{1,9}$/" ),
//"idUsuario" => idem al del objeto usuario
"dni" => array ( "lmin" => 1, "lmax" => 9, "exreg" => DNI ),
"empresa" => array ( "lmin" => 1, "lmax" => 50, "exreg" => "/^".ALFANUMERICOS_NOMBRE_EXT2."{1,100}$/" ),
"descripcion" => array ( "lmin" => 1, "lmax" => 300, "exreg" => "/^".ALFANUMERICOS_NOMBRE_EXT2."{1,300}$/" ),
"direccion" => array ( "lmin" => 1, "lmax" => 200, "exreg" => "/^".ALFANUMERICOS_NOMBRE_EXT."{1,200}$/" ),
"pedania" => array ( "lmin" => 1, "lmax" => 50, "exreg" => "/^".ALFANUMERICOS_NOMBRE_EXT."{1,50}$/" ),
"idPoblacion" => array ( "lmin" => 1, "lmax" => 5, "exreg" => "/^".NUMEROS."{1,5}$/" ),
"telefono1" =>  array ( "lmin" => 1, "lmax" => 13, "exreg" => TELEFONO),
"telefono2" => array ( "lmin" => 1, "lmax" => 13, "exreg" => TELEFONO),
"fax" =>  array ( "lmin" => 1, "lmax" => 13, "exreg" => TELEFONO),
//"email" => como el email del objeto usuario
"web" =>  array ( "lmin" => 1, "lmax" => 255, "exreg" => WEB),
"horario" => array ( "lmin" => 1, "lmax" => 255, "exreg" => "/^".ALFANUMERICOS_NOMBRE_EXT."{1,255}$/" ),
//"idSector" => ya definido en el objeto usuario
"validada" => array ( "lmin" => 1, "lmax" => 1, "exreg" => "/^(0|1)$/" ),
//"fecha" => como la fecha del objeto usuario, es un timestamp

//objeto oferta
"idOferta" => array ("lmin" => 1, "lmax" => 9, "exreg" => "/^".NUMEROS."{1,20}$/" ),
//"idEmpresa" => idem al objeto empresa
"oferta" => array ( "lmin" => 1, "lmax" => 50, "exreg" => "/^".ALFANUMERICOS_NOMBRE_EXT2."{1,50}$/" ),
"fechaIni" => array ( "lmin" => 1, "lmax" => 10, "exreg" => FECHA ),
"fechaFin" => array ( "lmin" => 1, "lmax" => 10, "exreg" => FECHA ),
"textoOferta" => array ( "lmin" => 1, "lmax" => 300, "exreg" => "/^".ALFANUMERICOS_NOMBRE_EXT2."{1,300}$/" ),
"textoCond" => array ( "lmin" => 1, "lmax" => 300, "exreg" => "/^".ALFANUMERICOS_NOMBRE_EXT2."{1,300}$/" ),
"activa" => array ( "lmin" => 1, "lmax" => 1, "exreg" => "/^(0|1)$/" ),
//"fecha" => como la fecha del objeto usuario, es un timestamp

//objeto noticia
"idNoticia" => array ("lmin" => 1, "lmax" => 9, "exreg" => "/^".NUMEROS."{1,20}$/" ),
//"idUsuario" => EXREG_idUsuario,
"titular" => array ( "lmin" => 1, "lmax" => 50, "exreg" => "/^".ALFANUMERICOS_NOMBRE_EXT2."{1,50}$/" ),
"resumen" => array ( "lmin" => 1, "lmax" => 200, "exreg" => "/^".ALFANUMERICOS_NOMBRE_EXT2."{1,200}$/" ),
"textoNot" => array ( "lmin" => 1, "lmax" => 300, "exreg" => "/^".ALFANUMERICOS_NOMBRE_EXT2."{1,300}$/" ),
"linkRef" => array ( "lmin" => 1, "lmax" => 255, "exreg" => WEB),
"fechaNot" => array ( "lmin" => 1, "lmax" => 10, "exreg" => FECHA ),
"fichero" => array ( "lmin" => 1, "lmax" => 1, "exreg" => "/^(0|1)$/" ),
//"activa" => como el campo de Oferta
//"fecha" => como la fecha del objeto usuario, es un timestamp

//objeto sector
//"idSector" => ya definido en el objeto usuario
"sector" => array ( "lmin" => 1, "lmax" => 50, "exreg" => "/^".ALFANUMERICOS_NOMBRE_EXT."{1,50}$/" ),

//objeto poblacion
//"idPoblacion" => ya definido en el objeto usuario
"poblacion" => array ( "lmin" => 1, "lmax" => 50, "exreg" => "/^".ALFANUMERICOS_NOMBRE_EXT."{1,50}$/" ),

//objeto acceso
//"idUsuario" => ACCESOS TIENE EL MISMO IDUSUARIO QUE LA TABLA USUARIOS
"pagina" => array ("lmin" => 1, "lmax" => 50, "exreg" => "/^".ALFANUMERICOS_PUNTO_GUION_SUBRAYADO."{1,50}$/" ),
"numVisitas" => array ("lmin" => 1, "lmax" => 9, "exreg" => "/^".NUMEROS."{1,20}$/" ),
//"ultAcceso" => es como FECHA, es un timestamps

//objeto consulta
"consulta" => array ("lmin" => 1, "lmax" => 334, "exreg" => "/^".SELECT."{1,334}$/" ),
"orden" => array ("lmin" => 1, "lmax" => 300, "exreg" => "/^".SELECT."{1,300}$/" ),
"objeto" => array ( "lmin" => 1, "lmax" => 20, "exreg" => OBJETO ),
"tamPag" => array ( "lmin" => 1, "lmax" => 2, "exreg" => "/^".NUMEROS."{1,2}$/" ),

);

//crea a partir del array anterior, contantes de expresiones de regulares que unen estas definiciones
//con un array en cada clase que controla que los datos para crear los objetos son correctos.
define("EXREG","EXREG_");
foreach ($camposBD as $clave => $valor) {
   $constante = EXREG.$clave;
   define($constante,$camposBD[$clave]["exreg"]);
}

//array con los mensajes de error de cada campo si no se cumple con el formato de su expresión regular
global $errores_exreg;

$errores_exreg = array (

//objeto usuario
"idUsuario" => "El ID. DE USUARIO debe ser un n&uacute;mero entero positivo." ,
"usuario" => "El USUARIO contiene caracteres no permitidos (s&oacute;lo letras, n&uacute;meros, -, _ o .).",
"password" => "La CONTRASE&Ntilde;A contiene caracteres no permitidos (s&oacute;lo letras y n&uacute;meros).",
"email" => "El CORREO ELECTR&Oacute;NICO no tiene un formato v&aacute;lido o contiene caracteres no permitidos.",
"tipoUsuario" => "El TIPO DE USUARIO no est&aacute; entre los permitidos.",
"nombre" => "El NOMBRE contiene caracteres no permitidos.",
"apellido1" => "El PRIMER APELLIDO contiene caracteres no permitidos.",
"apellido2" => "El SEGUNDO APELLIDO contiene caracteres no permitidos.",
"edad" => "La EDAD debe estar comprendida entre 18 y 99 a&ntilde;os.",
"sexo" => 'SEXO est&aacute; determinado como "H" para Hombre y "M" para Mujer.',
"idSector" => "El SECTOR se identifica con un n&uacute;mero entero positivo.",
"fecha" => "El formato de la FECHA no es correcto (AAAA-MM-DD hh:mm:ss).",

//objeto empresa
"idEmpresa" => "La ID. DE EMPRESA debe ser un n&uacute;mero entero positivo." ,
//"idUsuario" => idem al del objeto usuario
"dni" => "El DNI no tiene un formato v&aacute;lido o contiene caracteres no permitidos.",
"empresa" => "El NOMBRE DE LA EMPRESA contiene caracteres no permitidos.",
"descripcion" => "La DESCRIPCI&Oacute;N contiene caracteres no permitidos.",
"direccion" => "La DIRECCI&Oacute;N contiene caracteres no permitidos.",
"pedania" => "La PEDAN&Iacute;A contiene caracteres no permitidos.",
"idPoblacion" => "La POBLACI&Oacute;N se identifica con un n&uacute;mero entero positivo.",
"telefono1" =>  "El TEL&Eacute;FONO 1 no tiene un formato v&aacute;lido -formato: (999) 999 999 &oacute; 999-999-999 &oacute 999 999 999-",
"telefono2" => "El TEL&Eacute;FONO 2 no tiene un formato v&aacute;lido -formato: (999) 999 999 &oacute; 999-999-999 &oacute 999 999 999-",
"fax" =>  "El FAX no tiene un formato v&aacute;lido o contiene caracteres no permitidos.",
//"email" => como el email del objeto usuario
"web" =>  "La DIRECCI&Oacute;N WEB no tiene un formato v&aacute;lido o contiene caracteres no permitidos.",
"horario" => "El HORARIO contiene caracteres no permitidos.",
//"idSector" => ya definido en el objeto usuario
"validada" => "La empresa sólo puede estar VALIDADA (1) o NO (0).",
//"fecha" => como la fecha del objeto usuario, es un timestamp

//objeto oferta
"idOferta" => "El ID. de OFERTA debe ser un n&uacute;mero entero positivo." ,
//"idEmpresa" => idem al objeto empresa
"oferta" => "El NOMBRE DE LA OFERTA contiene caracteres no permitidos.",
"fechaIni" => "El formato de la FECHA no es correcto (DD/MM/AAAA).",
"fechaFin" => "El formato de la FECHA no es correcto (DD/MM/AAAA).",
"textoOferta" => "La DESCRIPCI&Oacute;N contiene caracteres no permitidos.",
"textoCond" => "Las CONDICIONES contienen caracteres no permitidos.",
"activa" => "Sólo puede estar ACTIVA (1) o NO (0).",
//"fecha" => como la fecha del objeto usuario, es un timestamp

//objeto noticia
"idNoticia" => "El ID. de NOTICIA debe ser un n&uacute;mero entero positivo." ,
//"idUsuario" => EXREG_idUsuario,
"titular" => "El TITULAR contiene caracteres no permitidos.",
"resumen" => "El RESUMEN contiene caracteres no permitidos.",
"textoNot" => "El TEXTO contiene caracteres no permitidos.",
"linkRef" => "El LINK EXTERNO no tiene un formato v&aacute;lido o contiene caracteres no permitidos.",
"fechaNot" => "El formato de la FECHA no es correcto (DD/MM/AAAA).",
"fichero" => "El FICHERO puede estar activo (1) o NO (0).",
//"activa" => como el campo de Oferta
//"fecha" => como la fecha del objeto usuario, es un timestamp

//objeto sector
//"idSector" => ya definido en el objeto usuario
"sector" => "El NOMBRE DEL SECTOR contiene caracteres no permitidos.",

//objeto poblacion
//"idPoblacion" => ya definido en el objeto usuario
"poblacion" => "La POBLACI&Oacute;N contiene caracteres no permitidos.",

//objeto acceso
//idUsuario es el mismo de la tabla usuarios, igual error
"pagina" => "La PAGINA contiene caracteres no permitidos.",
"numVisitas" => "El NUMERO DE VISITAS debe ser un n&uacute;mero entero positivo.",

//objeto consulta
"consulta" => "La CONSULTA contiene caracteres no permitidos." ,
"orden" => "El ORDEN contiene caracteres no permitidos." ,
"objeto" => "El OBJETO no est&aacute; entre los permitidos.",
"tamPag" => "El TAMA&Ntilde;O DE P&Aacute;GINA debe ser inferior a 99.",
);


//array con los mensajes de error de cada campo si no se cumple la longitud minima o máxima del campo
global $errores_longitud;

$errores_longitud = array (

//objeto usuario
"idUsuario" => "La longitud del ID. DE USUARIO es superior a 9 caracteres" ,
"usuario" => "La longitud del USUARIO supera los 30 caracteres.",
"password" => "La CONTRASE&Ntilde;A debe tener 6 caracteres como m&iacute;nimo y 20 como m&aacute;ximo.",
"email" => "La longitud del CORREO ELECTR&Oacute;NICO es superior a 50 caracteres.",
"tipoUsuario" => "La longitud de los valores permitidos para el TIPO DE USUARIO est&aacute; entre 5 y 13 caracteres.",
"nombre" => "La longitud del NOMBRE supera los 30 caracteres.",
"apellido1" => "La longitud del PRIMER APELLIDO supera los 30 caracteres.",
"apellido2" => "La longitud del SEGUNDO APELLIDO supera los 30 caracteres.",
"edad" => "La EDAD debe estar comprendida entre 18 y 99 a&ntilde;os.",
"sexo" => "El campo SEXO s&oacute;lo se identifica con un car&aacute;cter.",
"idSector" => "El valor del ID. del SECTOR es superior a 999.",
"fecha" => "El longitud de la FECHA supera los 19 caracteres.",

//objeto empresa
"idEmpresa" => "La longitud del ID. DE EMPRESA es superior a 9 caracteres" ,
//"idUsuario" => idem al del objeto usuario
"dni" => "La longitud del DNI es superior a 9 caracteres" ,
"empresa" => "La longitud del NOMBRE DE LA EMPRESA  supera los 50 caracteres.",
"descripcion" => "La longitud de la DESCRIPCI&Oacute;N supera los 300 caracteres.",
"direccion" => "La longitud de la DIRECCI&Oacute;N supera los 200 caracteres.",
"pedania" => "La longitud de la PEDAN&Iacute;A supera los 50 caracteres.",
"poblacion" => "El valor del ID. de la POBLACI&Oacute;N es superior a 99.999.",
"telefono1" =>  "La longitud del TEL&Eacute;FONO 1 supera los 13 caracteres.",
"telefono2" => "La longitud del TEL&Eacute;FONO 2 supera los 13 caracteres.",
"fax" =>  "La longitud del FAX  supera los 13 caracteres.",
//"email" => como el email del objeto usuario
"web" =>  "La longitud de la DIRECCI&Oacute;N WEB  supera los 255 caracteres.",
"horario" => "La longitud del HORARIO  supera los 255 caracteres.",
//"idSector" => ya definido en el objeto usuario
"validada" => "Solo es necesario un 0 oacute; 1 para VALIDAR o NO la empresa",
//"fecha" => como la fecha del objeto usuario, es un timestamp

//objeto oferta
"idOferta" => "La longitud del ID. de OFERTA debe ser un n&uacute;mero entero positivo." ,
//"idEmpresa" => idem al objeto empresa
"oferta" => "La longitud del NOMBRE DE LA OFERTA supera los 50 caracteres.",
"fechaIni" => "El longitud de la FECHA supera los 10 caracteres.",
"fechaFin" => "El longitud de la FECHA supera los 10 caracteres.",
"textoOferta" => "La longitud de la DESCRIPCI&Oacute;N supera los 300 caracteres.",
"textoCond" => "La longitud de las CONDICIONES supera los 300 caracteres.",
"activa" => "Solo es necesario un 0 oacute; 1 para ACTIVAR o NO",
//"fecha" => como la fecha del objeto usuario, es un timestamp

//objeto noticia
"idNoticia" => "La longitud del ID. de NOTICIA debe ser un n&uacute;mero entero positivo." ,
//"idUsuario" => EXREG_idUsuario,
"titular" => "La longitud del TITULAR supera los 50 caracteres.",
"resumen" => "La longitud del RESUMEN supera los 200 caracteres.",
"textoNot" => "La longitud del TEXTO supera los 300 caracteres.",
"linkRef" => "La longitud del LINK EXTERNO supera los 255 caracteres.",
"fechaNot" => "El longitud de la FECHA supera los 10 caracteres.",
"fichero" => "Solo es necesario un 0 oacute; 1 para activar o NO el FICHERO",
//"activa" => como el campo de Oferta
//"fecha" => como la fecha del objeto usuario, es un timestamp

//objeto sector
//"idSector" => ya definido en el objeto usuario
"sector" => "La longitud del NOMBRE DEL SECTOR supera los 50 caracteres.",

//objeto poblacion
//"idPoblacion" => ya definido en el objeto usuario
"poblacion" => "La longitud de la POBLACI&Oacute;N supera los 50 caracteres.",

//objeto acceso
//idUsuario es el mismo de la tabla usuarios, igual error
"pagina" => "La longitud de la PAGINA es superior a 50 caracteres",
"numVisitas" => "La longitud del NUMERO DE VISITAS es superior a 9 caracteres",

//objeto consulta
"consulta" => "La longitud de la CONSULTA es superior a 334 caracteres" ,
"orden" => "La longitud de la CONSULTA es superior a 300 caracteres" ,
"usuario" => "La longitud del nombre del OBJETO supera los 20 caracteres.",
"tamPag" => "El tamaño de página debe ser un número de dos dígitos",

);

?>
