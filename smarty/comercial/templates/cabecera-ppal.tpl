<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es">
<head>

    <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1" />
    <meta http-equiv="content-language" content="es" />

    <title>{$smarty.const.ETIQ_NOMBRE_WEB}</title>

    <meta name="description" content="{$smarty.const.ETIQ_NOMBRE_WEB}"/>

    <meta name="keywords" content="{$smarty.const.WEB_KEYWORDS}"/>

    <link rel="shortcut icon" href="{$smarty.const.IMG_ICONO_WEB}" type="image/x-icon"/>

    <link rel="stylesheet" media="screen,projection" type="text/css" href="{$smarty.const.CSS_PPAL}" />

    <link rel="stylesheet" media="screen,projection" type="text/css" href="{$smarty.const.CSS_EMPRESA}" />

    <link rel="stylesheet" media="screen,projection" type="text/css" href="{$smarty.const.CSS_MENU}" />
    
    {if $javascript}
    <!-- esto es sólo para ie6 y de todas maneras va mal xq lo descuadra todo...  -->
    <!-- si eso debería meter un menu especial para ie5, ie6 e ie7 tanto en menu como buscador... -->
    <!-- estos dos scripts el código no es mío, sino que forma parte del menú css en el que me he basado para mi menú principal-->
    <script language="JavaScript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js" type="text/javascript"></script>
    <script language="JavaScript" src="{$smarty.const.JS_MENU}" type="text/javascript"></script>
    <!-- fin jsmenu -->

    <script language="JavaScript" src="{$smarty.const.JS_TITULO}" type="text/javascript"></script>
    <script language="JavaScript" src="{$smarty.const.JS_NOTICIAS}" type="text/javascript"></script>
    <script language="JavaScript" src="{$smarty.const.JS_OFERTAS}" type="text/javascript"></script>
    <script language="JavaScript" src="{$smarty.const.JS_CONECTAR}" type="text/javascript"></script>
    <script language="JavaScript" src="{$smarty.const.JS_DESCONECTAR}" type="text/javascript"></script>
    <script language="JavaScript" src="{$smarty.const.JS_CONTENIDOS}" type="text/javascript"></script>
    <script language="JavaScript" src="{$smarty.const.JS_COMUN}" type="text/javascript"></script>
    {/if}
 
    {$metarefresh|default:""}	

</head>

<body>

{$metanoscript|default:""}	

<div id="contenedor">

  <div id="cabecera">
  
       <div id="logo">
           <a href="{$link_logo|default:""}" title="{$smarty.const.ETIQ_TIT_PAG_PPAL}"></a>
       </div>
  
       <div id="titulo">
  
            <div id="imagenes">
                 <a id="enlaceImagen" href="{$link_logo|default:""}" title="{$smarty.const.ETIQ_TIT_PORTAL}">
                        <img id="imagenCabecera" src="{$smarty.const.DIR_IMG_INICIAL}" alt="Enlace a {$smarty.const.ETIQ_TIT_PORTAL}"/>
                 </a>
            </div>
  
            <div id="letras"></div>
  
            <div id="menuConexion">
                   {if $nivel=={$smarty.const.ANONIMO} }
  		       {if $javascript}
                           <div id="formulario">
  	      	           <form action="{$link_conectar|default:""}" method="post" >
  			   <input type="text" name="usuariopp" id="usuariopp" value="Usuario"/>
  			   <input type="password" name="passwordpp" id="passwordpp" value="Password"/>
  			   </form>
  			   </div>
  		       {/if}
  		   {/if}
  	           {if $nivel!={$smarty.const.ANONIMO}}
    	                   <div id="links_bienvenido">
                           <a id="desconectar"  href="{$link_desconectar|default:""}">Desconectarse</a>
                           <a id="tucuenta" href="{$link_cuenta|default:""}">&Aacute;rea Personal</a>
                           <p id="bienvenido">Bienvenido {$nombreUsuario} !</p>
  	           {else}
    	                   <div id="links">
                           <a id="registrar" href="{$link_registro|default:""}">Registrarse</a>
  		           <a id="olvido"  href="{$link_recordar|default:""}">Recordar Datos</a>
  	                   <a id="conectar" href="{$link_conectar|default:""}">Conectar</a>
                   {/if}
       	                   </div>
           </div>
        </div>
       
        <div class="clear"></div>

       <div id="menu">
  	     <ul id="navmenu-h">
  	         <li class="bandera"><a class="esp" href="#" title="Espa&ntilde;ol">&nbsp;</a></li>
  	         <li class="bandera"><a class="eng" href="#" title="English">&nbsp;</a></li>
  	         <li id="menuB" class="{$clase_menuE|default:""}"><a href="{$empresas}">Empresas</a></li>
  	         <li id="menuO" class="{$clase_menuO|default:""}"><a href="{$ofertas}">Ofertas</a></li>
  	         <li id="menuN" class="{$clase_menuN|default:""}"><a href="{$noticias}">Noticias</a></li>
  	         <li id="menuA" class="{$clase_menuA|default:""}"><a href="{$asociacion}">La Asociaci&oacute;n</a></li>
  	         <li id="menuC"><a href="#">Tu comarca</a>
  		          <ul>
  		             <li class="submenu"><a href="http://www.adamuz.es/" >Adamuz</a></li>
  		             <li class="submenu"><a href="http://www.bujalance.es/" >Bujalance</a></li>
  		             <li class="submenu"><a href="http://www.aytocanetedelastorres.es/" >Ca&ntilde;ete de las Torres</a></li>
  		             <li class="submenu"><a href="http://www.ayunelcarpio.es/" >El Carpio</a></li>
  		             <li class="submenu"><a href="http://montoro.es:8080/opencms/opencms/PortalWeb/inicio.html" >Montoro</a></li>
  		             <li class="submenu"><a href="http://www.aytopedroabad.com/index3.php" >Pedro Abad</a></li>
  		             <li class="submenu"><a href="http://www.villadelrio.org/index3.php" >Villa del R&iacute;o</a></li>
  		             <li class="submenu"><a href="http://www.villafrancadecordoba.es/" >Villafranca</a></li>
  		             <li class="submenu"><a href="http://www.altoguadalquivir.com/" >Mancomunidad de Municipios</a></li>
  		             <li class="submenu"><a href="http://www.altoguadalquiviralminuto.com/" >Alto Guadalquivir al Minuto</a></li>
  		         </ul>
  	         </li>
               </ul>
       </div>
       
       <div class="clear"></div>

   </div>
