<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es">
<head>
    <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1" />
    <meta http-equiv="content-language" content="es" />

    <title>{$smarty.const.ETIQ_NOMBRE_WEB}</title>

    <meta name="description" content="{$smarty.const.ETIQ_NOMBRE_WEB}"/>

    <meta name="keywords" content="{$smarty.const.WEB_KEYWORDS}"/>

    <link rel="shortcut icon" href="{$smarty.const.IMG_ICONO_WEB}" type="image/x-icon"/>

    <link rel="stylesheet" media="screen,projection" type="text/css" href="{$smarty.const.CSS_COMUN}" />

    <link rel="stylesheet" media="screen,projection" type="text/css" href="{$smarty.const.CSS_EMPRESA}" />    

    <script language="JavaScript" src="{$smarty.const.JS_COMUN2}" type="text/javascript"></script>

</head>

<body>

<div id="contenedor">

     <div id="cabecera">

	     <div id="logo">
	         <a href="{$linkCabecera|default:""}" title="{$smarty.const.ETIQ_PAG_PPAL}">&nbsp;</a>
	     </div>

	     <div id="titulares">
	     	  <div id="titulo">
	     	       <a href="{$linkCabecera|default:""}" title="{$smarty.const.ETIQ_PAG_PPAL}">&nbsp;</a>
		  </div>
		  <div id="pagina">
		        <h1>{$tituloPagina|default:"&nbsp;"}</h1>
		  </div>
	     </div>

     </div>
     
     <div class="clear"></div>

     <div id="mensajes" {$class_mensajes|default:""}>
     	 {foreach item="mensaje" from=$mensajes|default:""}
	 	  <p>{$mensaje|default:"&nbsp;"}</p>
	 {/foreach}
     </div>
     

     <div class="clear"></div>
