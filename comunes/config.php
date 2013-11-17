<?php

// por si el servidor da problemas cuando el cliente restringe cookies
ini_set("session.use_only_cookies", 0);

header("Content-Type: text/html; charset=iso-8859-1");
//poner la ruta del subdirectorio dentro del directorio raiz donde ponemos la
//estructura de directorios de esta web
//IMPORTANTE: comenzar con la barra para que la ruta sea absoluta.
//->define("RAIZ","/");
//establece la ruta absoluta en el servidor hasta donde está el directorio anterior RAIZ
//ej. este archivo config.php está en la ruta RUTA_ABS.RAIZ."/comunes/config.php"
//más abajo todas páginas se definen de forma relativa a la estructura de directorios que hay
//->define("RUTA_ABS",dirname(dirname(__FILE__))."/");
//definir donde está el directorio smarty. O literalmente (C:/...)
//o yo he preferido ir retrocediendo en directorios a partir de donde está este config.php
//en mi servidor
//->define("RUTA_SMARTY",dirname(dirname(__FILE__))."/smarty"."/comercial");

//////////////////////////////////////////////////////////////////////////////////////////////////////////////

// definiciones para la instalación en linux
//define("RAIZ","/comercial/");
//define("RUTA_ABS",dirname(dirname(__FILE__))."/");
//define("RUTA_SMARTY",dirname(dirname(dirname(dirname(dirname(__FILE__)))))."/smarty"."/comercial");
//define("RUTA_SMARTY",dirname(dirname(__FILE__))."/smarty"."/comercial");

// definiciones para la instalación en pendrive
define("RAIZ","/comercial/");
define("RUTA_ABS",dirname(dirname(__FILE__))."/");
//define("RUTA_SMARTY",dirname(dirname(dirname(dirname(__FILE__))))."/smarty"."/comercial");
define("RUTA_SMARTY",dirname(dirname(__FILE__))."/smarty"."/comercial");


// definiciones para la instlacion del netbook
//define("RAIZ","/comercial/");
//define("RUTA_ABS",dirname(dirname(__FILE__))."/");
//define("RUTA_SMARTY",dirname(dirname(dirname(dirname(__FILE__))))."/smarty"."/comercial");
//->define("RUTA_SMARTY",dirname(dirname(__FILE__))."/smarty"."/comercial");

// definiciones para el servidor gratuito remoto
//define("RAIZ","/");
//define("RUTA_ABS",dirname(dirname(__FILE__))."/");
//define("RUTA_SMARTY",dirname(dirname(__FILE__))."/smarty"."/comercial2");

/////////////////////////////////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////links a las distintas páginas

//reflejan el directorio donde se encuentran dentro de la propia estructura de directorios establecida

//hojas de estilo
define("CSS_PPAL",RAIZ."css/principal.css");
define("CSS_MENU",RAIZ."css/menu.css");
define("CSS_COMUN",RAIZ."css/comun.css");
define("CSS_EMPRESA",RAIZ."css/empresa.css");

//modulos javascript
define("JS_MENU",RAIZ."javascript/menu.js");
define("JS_TITULO",RAIZ."javascript/titulo.js");
define("JS_NOTICIAS",RAIZ."javascript/noticias.js");
define("JS_OFERTAS",RAIZ."javascript/ofertas.js");
define("JS_CONECTAR",RAIZ."javascript/conectar.js");
define("JS_DESCONECTAR",RAIZ."javascript/desconectar.js");
define("JS_CONTENIDOS",RAIZ."javascript/cargadorContenidos.js");
define("JS_COMUN",RAIZ."javascript/comun.js");
define("JS_COMUN2",RAIZ."javascript/comun2.js");

/////////página principal
define("PAG_PPAL","inicio.php");
//paginas principales
define("PAG_PPAL_EMPRESAS","empresas.php");
define("PAG_PPAL_OFERTAS","ofertas.php");
define("PAG_PPAL_NOTICIAS","noticias.php");
define("PAG_PPAL_ASOCIACION","asociacion.php");
define("PAG_PPAL_NORMATIVA","normativa.php");
define("PAG_PPAL_INFORMACION","informacion.php");

/////////archivo con funciones comunes usadas en muchas partes de la aplicación
define("COMUN","comunes/"."comun.php");  

/////////archivos donde están las clases de los distintos objetos que tratamos:

define("DIR_CLASES","clases/");
//fichero de datos de configuración de los objetos y la BD.
define("CONFIG_BD",DIR_CLASES."configbd.php");
//clase "madre" de todas las siguientes. Define un objeto general y la conexión y desconexión a la BD
define("CLA_OBJ",DIR_CLASES."ObjetoBD.class.php");
//clase genérica que define un objeto que es como la extensión de cualquiera de los anteriores
//y que define métodos para realizar la consulta sobre cualquier objeto anterior, recuperar los datos
//y moverse por ellos como si fuesen páginas o bloques
define("CLA_CON",DIR_CLASES."Consulta.class.php");
//clases de los distintos objetos, se corresponden con tablas en la BD
define("CLA_USU",DIR_CLASES."Usuario.class.php");
define("CLA_EMP",DIR_CLASES."Empresa.class.php");
define("CLA_OFE",DIR_CLASES."Oferta.class.php");
define("CLA_NOT",DIR_CLASES."Noticia.class.php");
define("CLA_SEC",DIR_CLASES."Sector.class.php");
define("CLA_POB",DIR_CLASES."Poblacion.class.php");
define("CLA_ACC",DIR_CLASES."Acceso.class.php");
define("CLA_USUACC",DIR_CLASES."UsuarioAcceso.class.php");
define("CLA_EMPPOBSECUSU",DIR_CLASES."EmpPoblSecUsu.class.php");
define("CLA_OFEEMP",DIR_CLASES."OfertaEmpresa.class.php");
define("CLA_NOTUSU",DIR_CLASES."NoticiaUsuario.class.php");

////////////////// DEFINICIONES DE LAS PÁGINAS QUE SOPORTAN EL TRATAMIENTO DE LA APLICACION

define("DIR_EJEC","area-personal/");
/////////páginas de conexión y desconexión
define("PAG_CONEX",DIR_EJEC."conectar.php");
define("PAG_DECONEX",DIR_EJEC."desconectar.php");
define("PAG_RECORDAR",DIR_EJEC."recordar.php");
/////////páginas donde se tratan los distintos objetos, sirve para dar de alta (registro) o actualizarlos
define("PAG_REG_PPAL",DIR_EJEC."registro.php"); //página de inicio de todo registro
define("PAG_USUARIOS",DIR_EJEC."usuarios.php");
define("PAG_EMPRESAS",DIR_EJEC."empresas.php");
define("PAG_OFERTAS",DIR_EJEC."ofertas.php");
define("PAG_NOTICIAS",DIR_EJEC."noticias.php");

//páginas que dan una visualización previa de distintas entidades
define("PAG_VPREV_EMP",DIR_EJEC."vista_empresa.php");
//página de opciones de gestion de los usuarios
define("PAG_AREA_PERSONAL",DIR_EJEC."cuenta.php");
//páginas que gestionan los distintos listados de la parte de gestión
define("PAG_GALERIA_NOTICIAS",DIR_EJEC."galeria_noticias.php");
define("PAG_GALERIA_OFERTAS",DIR_EJEC."galeria_ofertas.php");
define("PAG_LIST_ACC_GRAL",DIR_EJEC."list_accesos.php");
define("PAG_LIST_ACC_USU",DIR_EJEC."list_acc_usuario.php");
define("PAG_LIST_EMP",DIR_EJEC."list_empresas.php");
define("PAG_LIST_NOT",DIR_EJEC."list_noticias.php");
define("PAG_LIST_NOT_USU",DIR_EJEC."list_noticias_usuario.php");
define("PAG_LIST_OFE",DIR_EJEC."list_ofertas.php");
define("PAG_LIST_OFE_EMP",DIR_EJEC."list_ofertas_empresa.php");
define("PAG_LIST_PTE",DIR_EJEC."list_empresas_pend.php");
define("PAG_LIST_USU",DIR_EJEC."list_usuarios.php");


//directorio de imagenes
define("DIR_IMG",RAIZ."imagenes/");
define("DIR_IMG_CAB",RAIZ."imagenes/cabecera/");
define("DIR_IMG_INICIAL",DIR_IMG_CAB."rio.gif");

//links de imagenes
define("IMG_ICONO_WEB",DIR_IMG."favicon.ico");
define("IMG_HORARIO",DIR_IMG."icono_fecha.gif");
define("IMG_DIRECCION",DIR_IMG."localizacion.gif");
define("IMG_TELEFONO",DIR_IMG."telefono.gif");
define("IMG_FAX",DIR_IMG."fax.jpg");
define("FLECHA_SUBIR",DIR_IMG."flecha-subir.gif");
define("FLECHA_BAJAR",DIR_IMG."flecha-bajar.gif");
define("PALETA",DIR_IMG."paleta.jpg");


//plantillas
define("TPL_CABECERA_PPAL","cabecera-ppal.tpl");
define("TPL_SUBPIE_PPAL","subpie-ppal.tpl");
define("TPL_PIE_PPAL","pie-ppal.tpl");
define("TPL_CABECERA","cabecera.tpl");
define("TPL_PIE","pie.tpl");
define("TPL_MENSAJE","mensaje.tpl");
define("TPL_CONECTAR","conectar.tpl");
define("TPL_RECORDAR","recordar.tpl");
define("TPL_REGISTRO","registro.tpl");
define("TPL_FICHA_USU","formUsuario.tpl");
define("TPL_FICHA_EMP","formEmpresa.tpl");
define("TPL_FICHA_OFE","formOferta.tpl");
define("TPL_FICHA_NOT","formNoticia.tpl");
define("TPL_AREA_PERSONAL","cuenta.tpl");
define("TPL_LISTADO","listado.tpl");
define("TPL_VISTA_EMP","vistaEmpresa.tpl");
define("TPL_VISTA_NOT","vistaNoticia.tpl");
define("TPL_VISTA_OFE","vistaOferta.tpl");
define("TPL_GAL_OFE","galeria_ofertas.tpl");
define("TPL_GAL_NOT","galeria_noticias.tpl");
define("TPL_BUSCADOR","buscador.tpl");
define("TPL_TEXTO_INICIO","texto-inicio.tpl");
define("TPL_TEXTO_ASOCIACION","texto-asociacion.tpl");
define("TPL_TEXTO_NORMATIVA","texto-normativa.tpl");
define("TPL_INFORMACION","informacion.tpl");

//////////////////////////////////links a las plantillas

define("SMARTY_TEMPLATE_DIR",RUTA_SMARTY."/templates/");
define("SMARTY_COMPILE_DIR",RUTA_SMARTY."/templates_c/");
define("SMARTY_CONFIG_DIR",RUTA_SMARTY."/configs/");
define("SMARTY_CACHE_DIR",RUTA_SMARTY."/cache/");

//tamaños de página de los distintos listados que hay...
//en la página principal...
define("TAM_PAG_EMP_PPAL", 2);
define("TAM_PAG_OFE_PPAL", 3);
define("TAM_PAG_NOT_PPAL_NOR", 5);
define("TAM_PAG_NOT_PPAL_RED", 3);

//o en las páginas de gestión de los datos...
define("TAM_PAG_USU", 10);
define("TAM_PAG_EMP", 10);
define("TAM_PAG_OFE", 10);
define("TAM_PAG_NOT", 10);
define("TAM_PAG_ACC", 10);

//tamaños y dimensiones máximos de archivos de imagen

define("TAM_IMG_EMP_BYTES",819200);
define("TAM_IMG_EMP","100 KB");
define("TAM_IMG_OFE_BYTES",8388608);
define("TAM_IMG_OFE","1 MB");
define("ANCHO_IMG_EMP",170);
define("ALTO_IMG_EMP",170);
define("ANCHO_IMG_OFE",300);
define("ALTO_IMG_OFE",250);

define("PREFIJO_ARCH_EMP","logo");
define("EXT_GEN_ARCH_EMP",".gif");
define("DIR_TMP_ARCH","imgUsuarios/temporal/");
define("DIR_ARCH_EMP","imgUsuarios/logos/");
define("RETORNO_DIR_EJEC","../../".DIR_EJEC); //estos directorios están en función del a ubicación de la página que sube archivos

define("PREFIJO_ARCH_OFE","oferta");
define("EXT_GEN_ARCH_OFE",".gif");
define("DIR_ARCH_OFE","imgUsuarios/ofertas/");


////////////////// ETIQUETAS:

//generales
define("ETIQ_NOMBRE_WEB","Centro Comercial Alto Guadalquivir");
define("WEB_KEYWORDS","asociaci&oacute;n, comercio, ofertas, buscador, comarca, guadalquivir, alto guadalquivir");

//etiquetas de tipo de página / titulo de pagina
define("AREA_RESTRINGIDA","A R E A &nbsp; R E S T R I N G I D A");
define("BIENVENIDO","Bienvenido, ");
define("ENHORABUENA","¡ E N H O R A B U E N A !");
define("ERROR","E R R O R");
define("ERRORBD","E R R O R &nbsp;&nbsp; D E &nbsp;&nbsp; B A S E &nbsp;&nbsp; D E &nbsp;&nbsp; D A T O S");
define("HASTA_PRONTO","¡ H A S T A&nbsp;&nbsp;&nbsp;&nbsp;P R O N T O !");
define("INFORMACION","I N F O R M A C I &Oacute; N");

define("ETIQ_TIT_LIST_ACC","listado de accesos");
define("ETIQ_TIT_LIST_ACCUSU","accesos del usuario ");
define("ETIQ_TIT_LIST_EMP","listado de empresas");
define("ETIQ_TIT_LIST_NOT","listado de noticias");
define("ETIQ_TIT_LIST_NOTUSU","noticias del usuario ");
define("ETIQ_TIT_LIST_OFE","listado de ofertas");
define("ETIQ_TIT_LIST_OFEEMP","ofertas de ");
define("ETIQ_TIT_LIST_USU","listado de usuarios");
define("ETIQ_TIT_MOD_EMP","modificaci&oacute;n de datos de la empresa");
define("ETIQ_TIT_MOD_NOT","modificaci&oacute;n de una noticia");
define("ETIQ_TIT_MOD_OFE","modificaci&oacute;n de datos de una oferta");
define("ETIQ_TIT_MOD_USU","modificaci&oacute;n de datos del usuario");
define("ETIQ_TIT_PAG_CONEX","conexión a tu espacio personal");
define("ETIQ_TIT_PAG_PPAL","P&aacute;gina Principal");
define("ETIQ_TIT_PAG_RECORDAR","solicitud de recuperaci&oacute;n de datos personales");
define("ETIQ_TIT_PORTAL","Portal Centro Comercial Abierto Alto Guadalquivir");
define("ETIQ_TIT_REG_CLIENTE","solicitud de registro de cliente");
define("ETIQ_TIT_REG_EMP","solicitud de registro de empresa");
define("ETIQ_TIT_REG_NOT","alta de nueva noticia");
define("ETIQ_TIT_REG_OFE","alta de nueva oferta");
define("ETIQ_TIT_REG_SOCIO","solicitud de registro de socio");
define("ETIQ_TIT_REG_TIPUSU","solicitud de registro");
define("ETIQ_TIT_VISTA_PREV_EMP","vista previa de empresa");
define("ETIQ_TIT_INFO","información acerca del desarrollo de este sitio");

//etiquetas de subtítulos de página
define("ETIQ_SUBTIT_AREA_PERSONAL","Esta es tu &Aacute;rea Personal. &nbsp; Elige una de las opciones:");
define("ETIQ_SUBTIT_AREA_RESTRINGIDA","p&aacute;gina con acceso restringido");
define("ETIQ_SUBTIT_ENHORABUENA","El registro se ha realizado correctamente");
define("ETIQ_SUBTIT_ERROR","Se ha producido el siguiente error:");
define("ETIQ_SUBTIT_ERRORBD","Se ha producido el siguiente error al tratar con la Base de Datos:");
define("ETIQ_SUBTIT_HASTA_PRONTO","Gracias por su visita");
define("ETIQ_SUBTIT_INFORMACION","Atenci&oacute;n:");
define("ETIQ_SUBTIT_INTRO_CONEX","Introduzca sus datos de acceso");
define("ETIQ_SUBTIT_INTRO_DATOS","Introduce los siguientes datos:");
define("ETIQ_SUBTIT_INTRO_RECORDAR","Introduzca la direcci&oacute;n de correo electr&oacute;nico con la que se di&oacute; de alta");
define("ETIQ_SUBTIT_REG_TIPUSU","¿ Cu&aacute;l es su Perfil ?");
define("ETIQ_SUBTIT_REPASAR_DATOS","Revise los datos y el formato con el que se visualizará en la Web");

//etiquetas de links a páginas
define("ETIQ_LINK_AREA_PERSONAL","Volver a su &Aacute;rea Personal");
define("ETIQ_LINK_IR_AREA_PERSONAL","Acceder a su &Aacute;rea Personal");
define("ETIQ_LINK_CORREO_USU",'Escribir Correo al Usuario');
define("ETIQ_LINK_DECONEX","Desconectar");
define("ETIQ_LINK_OLVIDO","¿Ha olvidado sus datos?");
define("ETIQ_LINK_PAG_PPAL","Ir a la P&aacute;gina Principal");
define("ETIQ_LINK_REG","Si a&uacute;n no es miembro, puede registrarse aqu&iacute;");
define("ETIQ_LINK_VOLVER",'Volver a la P&aacute;gina Anterior');
define("ETIQ_LINK_VOLVER_LIST",'Volver al Listado');
define("ETIQ_PAG_PPAL","P&aacute;gina Principal");


//etiquetas de mensajes estándar
define("ETIQ_ACCESO_RESERVADO","Acceso Reservado a Usuarios Registrados");
define("ETIQ_ACTU_NOT_OK","La Noticia se ha actualizado correctamente");
define("ETIQ_ACTU_OFE_OK","La Oferta se ha actualizado correctamente");
define("ETIQ_ALTA_NOT_OK","La Noticia se ha dado de alta correctamente");
define("ETIQ_ALTA_OFE_OK","La Oferta se ha dado de alta correctamente");
define("ETIQ_ARCHIVO_CARACTERES_NO_VALIDOS","El nombre del archivo contiene caracteres no v&aacute;lidos.");
define("ETIQ_AVISO_BORRAR",'¿ Est&aacute; seguro que desea dar de baja el Usuario ?');
define("ETIQ_AVISO_BORRAR_NOT",'¿ Est&aacute; seguro que desea borrar esta Noticia ?');
define("ETIQ_AVISO_BORRAR_OFE",'¿ Est&aacute; seguro que desea borrar esta Oferta ?');
define("ETIQ_AVISO_PROXY","(Es posible que si se conecta bajo proxy no pueda subir im&aacute;genes)");
define("ETIQ_BUSCADOR_EMPRESAS1","empresas encontradas de...");
define("ETIQ_BUSCADOR_EMPRESAS2","busca empresas de...");
define("ETIQ_BUSCADOR_OFERTAS1","ofertas encontradas de...");
define("ETIQ_BUSCADOR_OFERTAS2","busca ofertas de...");
define("ETIQ_CAMPO_SIN_EXREG_ASOCIADA","El campo NO se ajusta a ning&uacute;n patr&oacute;n");
define("ETIQ_CLIENTE_NO_EMPRESA","Un cliente no puede registrar una empresa. Para ello, h&aacute;gase socio");
define("ETIQ_CLIENTE_NO_VISTA_EMP","Como cliente, usted NO tiene empresa asociada");
define("ETIQ_COMPLETAR_CAMPOS","Por favor, completa los campos resaltados m&aacute;s abajo");
define("ETIQ_COMPLETAR_USU_PASS","Rellene su usuario y/o la contraseña");
define("ETIQ_CONEX_OK","Se ha autentificado correctamente");
define("ETIQ_CONSULTA_FALLIDA","Se ha producido un error al realizar la consulta");
define("ETIQ_CORREO_NO_ENVIADO","Ha ocurrido un error y el correo no puede ser enviado.<br></br>Por favor, int&eacute;ntelo m&aacute;s tarde.");
define("ETIQ_CORREO_NO_EXISTE","La direcci&oacute;n indicada no est&aacute; dada de alta en nuestro portal");
define("ETIQ_DATOS_CONEX_INCORRECTOS","El nombre de usuario y/o la contraseña no es correcto. Por favor, inténtelo de nuevo");
define("ETIQ_DECONEX_OK","Ha sido desconectado correctamente del sistema.");
define("ETIQ_DIM_MAX_IMG_EMP","Las dimensiones de la imagen deben ser de ".ANCHO_IMG_EMP."x".ALTO_IMG_EMP." pixeles.");
define("ETIQ_DIM_MAX_IMG_OFE","Las dimensiones de la imagen deben ser de ".ANCHO_IMG_OFE."x".ALTO_IMG_OFE." pixeles.");
define("ETIQ_DNI_NO_VALIDO","El DNI no es v&aacute;lido.");
define("ETIQ_DNI_YA_EXISTE","El DNI ya est&aacute; dado de alta. Si tiene otra cuenta registrada, recup&eacute;rela.");
define("ETIQ_ELIJA_TIPO_USUARIO","Por favor, elige un tipo de usuario para realizar el registro");
define("ETIQ_EMAIL_YA_EXISTE","La direcci&oacute;n de CORREO ELECTR&Oacute;NICO ya existe. Si tiene otra cuenta registrada, recup&eacute;rela.");
define("ETIQ_EMP_ACTU","La Empresa ha sido actualizada");
define("ETIQ_EMP_ACTU_ERR_ARCH","La Empresa ha sido actualizada<br></br>Pero NO se ha podido guardar el archivo de Imagen.<br></br>Por favor, inténtelo de nuevo actualizando la oferta.");
define("ETIQ_EMP_PDTE","Datos Actualizados.<br>Su empresa queda oculta pendiente de validación.");
define("ETIQ_EMP_PDTE_ERR_ARCH","Datos Actualizados. Su empresa queda oculta pendiente de validación.<br></br>Pero NO se ha podido guardar el archivo de Imagen.<br></br>Por favor, inténtelo de nuevo actualizando la oferta.");
define("ETIQ_EMPRESA_NO_ENCONTRADA","La Empresa ya no existe o est&aacute; pendiente de validaci&oacute;n");
define("ETIQ_EMPRESA_NO_EXISTE","El Usuario NO tiene Empresa Asociada");
define("ETIQ_EMPRESA_REGISTRADA","Ya tiene su empresa registrada");
define("ETIQ_ENVIO_OK","Se le ha enviado un correo a su cuenta con<br>las instrucciones para recuperar su clave.<br><br>Por favor, revise tambi&eacute;n su bandeja de correo no deseado.");
define("ETIQ_ERR_ADJUNTAR_ARCHIVO","Ha ocurrido un error con el archivo. Adj&uacute;ntelo de nuevo.");
define("ETIQ_ERR_ARCHIVO_PERDIDO","No se ha podido guardar el archivo de Imagen.<br></br>Por favor, inténtelo de nuevo actualizando la oferta.");
define("ETIQ_ERR_SUBIR_ARCHIVO","Se produjo un error al subir el archivo.<br></br>Por favor, int&eacute;ntelo de nuevo");
define("ETIQ_ERROR_BORRAR_ACCESOS","Se ha producido un error. No ha sido posible limpiar el historial");
define("ETIQ_FECHA_INF_ACTUAL","La FECHA debe ser igual o posterior al día de hoy");
define("ETIQ_FECHA_NO_VALIDA","La FECHA introducida NO se corresponde con un día v&aacute;lido.");
define("ETIQ_FORMATO_IMAGEN_NO_VALIDO","La imagen debe tener formato gif o jpeg.");
define("ETIQ_LIMP_HIST_OK","Se ha limpiado el historial del usuario.");
define("ETIQ_MAL_LLAMADA","Los par&aacute;metros de llamada no son correctos");
define("ETIQ_NO_HAY_EMPRESAS","No se han encontrado empresas<br>con los criterios seleccionados");
define("ETIQ_NO_HAY_NOTICIAS","No hay noticias vigentes en nuestro portal");
define("ETIQ_NO_HAY_OFERTAS","No se han encontrado ofertas<br>con los criterios seleccionados");
define("ETIQ_NO_HAY_OFERTAS_EMPRESA","No se han encontrado ofertas para esta empresa");
define("ETIQ_NO_HAY_REGISTROS","El listado est&aacute; vac&iacute;o. No hay registros que mostrar");
define("ETIQ_NO_ID_EMPRESA","Debe identificar su empresa en la petición");
define("ETIQ_NOTICIA_ELIMINADA","La Noticia ha sido eliminada");
define("ETIQ_NOTICIA_NO_ENCONTRADA","La noticia que se ha seleccionado ya no est&aacute disponible");
define("ETIQ_NOTICIA_NO_EXISTE","No existe la Noticia indicada");
define("ETIQ_OFERTA_ELIMINADA","La Oferta ha sido eliminada");
define("ETIQ_OFERTA_NO_ENCONTRADA","La Oferta ya no existe o est&aacute; pendiente de validaci&oacute;n");
define("ETIQ_OFERTA_NO_EXISTE","No existe la Oferta indicada");
define("ETIQ_OPER_REALIZADA","Por favor, vuelva a su página personal.<br></br>La operaci&oacute;n se realiz&oacute; correctamente.");
define("ETIQ_PASSWORD_DISTINTA","Aseg&uacute;rate de que la CONTRASE&Ntilde;A introducida es igual en ambos campos.");
define("ETIQ_PASSWORD_NO_PERMITIDA",'No se permite "Password" como CONTRASE&Ntilde;A v&aacute;lida');
define("ETIQ_REG_IGUALES","No se ha modificado ning&uacute;n dato");
define("ETIQ_REGISTRARSE","Registrarse");
define("ETIQ_REGISTRO_OK_EMP","Su empresa aparecerá en nuestro portal una vez haya sido validada.");
define("ETIQ_REGISTRO_OK_EMP_ERR_ARCH","Su empresa aparecerá en nuestro portal una vez haya sido validada<br></br>Aunque NO se ha podido guardar su Logotipo.<br></br>Por favor, inténtelo de nuevo conect&aacute;ndose y actualizando la oferta.");
define("ETIQ_REGISTRO_OK_USU","¡ Ya es cliente nuestro ! <br><br> Vaya a la Página Principal para Conectarse.");
define("ETIQ_TAM_MAX_IMG_EMP","El tama&ntilde;o de la imagen debe ser inferior a ".TAM_IMG_EMP);
define("ETIQ_TAM_MAX_IMG_OFE","El tama&ntilde;o de la imagen debe ser inferior a ".TAM_IMG_OFE);
define("ETIQ_USUARIO_ACTUALIZADO","El Usuario ha sido actualizado");
define("ETIQ_USUARIO_ELIMINADO","El Usuario ha sido eliminado");
define("ETIQ_USUARIO_NO_AUTORIZADO","El Usuario NO tiene privilegios para acceder a esta p&aacute;gina");
define("ETIQ_USUARIO_NO_CONECTADO","Usted NO se encuentra conectado");
define("ETIQ_USUARIO_NO_EXISTE","El Usuario NO existe");
define("ETIQ_USUARIO_NO_PERMITIDO",'No se permite "Usuario" como nombre de USUARIO v&aacute;lido');
define("ETIQ_USUARIO_REGISTRADO","El usuario ya se encuentra registrado");
define("ETIQ_USUARIO_YA_CONEX","Usted ya se encuentra conectado");
define("ETIQ_USUARIO_YA_EXISTE","El USUARIO ya est&aacute; ocupado. Por favor, elija uno nuevo.");


//etiquetas de botones
define("ETIQ_BT_ACTIVAR_NOT","Activar Noticia");
define("ETIQ_BT_ACTIVAR_OFE","Activar Oferta");
define("ETIQ_BT_ACTU_EMP","Actualizar");
define("ETIQ_BT_ACTU_NOT","Actualizar Noticia");
define("ETIQ_BT_ACTU_OFE","Actualizar Oferta");
define("ETIQ_BT_ACTUALIZAR_USU","Actualizar Datos del Usuario");
define("ETIQ_BT_ALTA","Dar de Alta");
define("ETIQ_BT_ALTA_NOT","Subir Noticia");
define("ETIQ_BT_ALTA_OFE","Subir Oferta");
define("ETIQ_BT_BAJA","Dar de Baja el Usuario");
define("ETIQ_BT_BAJA_NOT","Eliminar esta Noticia");
define("ETIQ_BT_BAJA_OFE","Eliminar esta Oferta");
define("ETIQ_BT_CANCELAR","Cancelar");
define("ETIQ_BT_CONECTAR","Conectar");
define("ETIQ_BT_CONF_ACTU_EMP","Confirmar la Actualización");
define("ETIQ_BT_CONF_ALTA_EMP","Confirmar Alta");
define("ETIQ_BT_CONFIRMAR_BAJA","Confirmar la Baja del Usuario");
define("ETIQ_BT_CONFIRMAR_BAJA_NOT","Confirmar Borrado de la Noticia");
define("ETIQ_BT_CONFIRMAR_BAJA_OFE","Confirmar Borrado de la Oferta");
define("ETIQ_BT_CONTINUAR","Continuar");
define("ETIQ_BT_DESACTIVAR_NOT","Desactivar Noticia");
define("ETIQ_BT_DESACTIVAR_OFE","Desactivar Oferta");
define("ETIQ_BT_ENVIAR_CORREO","Enviar Correo");
define("ETIQ_BT_LIMPIAR","Limpiar Datos del Formulario");
define("ETIQ_BT_LIST_OFERTAS","Ver Ofertas de la Empresa");
define("ETIQ_BT_MOD_TIP_USU","Modificar el Tipo de Usuario");
define("ETIQ_BT_VALIDAR_EMP","Validar Empresa");
define("ETIQ_BT_VER_ACCESOS","Ver Accesos del Usuario") ;
define("ETIQ_BT_VER_EMPRESA","Ver Empresa del Usuario");
define("ETIQ_BT_VER_USU","Ver Datos del Usuario");
define("ETIQ_BT_VISTA_EMP","Vista Web");
define("ETIQ_BT_VISU_ACTU_EMP","Actualizar");
define("ETIQ_BT_VISU_ALTA_EMP","Dar de Alta");
define("ETIQ_LINK_VOLVER_EMPRESA","Ir a la Empresa");



//clases
define("CLAS_ERROR",' class = "error"');

////////////////// FIN ETIQUETAS

//y por último se hace un include de las funciones comunes, la plantilla y las clases
//pues es algo que siempre se usa. con require_once, solo se insertará una vez
require_once(RUTA_ABS.COMUN);
require_once(RUTA_ABS.CONFIG_BD);
require_once(RUTA_ABS.CLA_OBJ);
require_once(RUTA_ABS.CLA_CON);
require_once(RUTA_ABS.CLA_USU);
require_once(RUTA_ABS.CLA_EMP);
require_once(RUTA_ABS.CLA_OFE);
require_once(RUTA_ABS.CLA_NOT);
require_once(RUTA_ABS.CLA_ACC);
require_once(RUTA_ABS.CLA_SEC);
require_once(RUTA_ABS.CLA_POB);
require_once(RUTA_ABS.CLA_USUACC);
require_once(RUTA_ABS.CLA_OFEEMP);
require_once(RUTA_ABS.CLA_NOTUSU);
require_once(RUTA_ABS.CLA_EMPPOBSECUSU);

//y por último, smarty (no tiene ruta porque está configurado en el fichero php.ini);

// definición para instalación en linux
//require_once('/opt/lampp/lib/php/smarty/Smarty.class.php');
//no tiene ruta porque está configurado en el fichero php.ini para esta instalación);
//require_once('Smarty.class.php');
//->require_once('/opt/lampp/htdocs/comercial2/lib/php/smarty/Smarty.class.php');

// definición para el pendrive
//y por último, smarty (no tiene ruta porque está configurado en el fichero php.ini);
//require_once('E:\MovAmp_Joomla\mnt\usr\local\php\extensions\smarty\Smarty.class.php');
require_once('w:\var\www\comercial\lib\php\smarty\Smarty.class.php');

// definicion para el servidor netbook local
//require_once('C:\wamp\bin\php\smarty\Smarty.class.php');
//->require_once('C:\wamp\www\comercial\lib\php\smarty\Smarty.class.php');

// definición para el servidor remoto
//require_once('/home/vol5/0fees.net/fees0_11963213/ccaltoguadalquivir.is-great.net/htdocs/lib/smarty/Smarty.class.php');

?>
