var imagenAMostrar = 1 ; // variable global para conocer qué foto se está mostrando y cambiar
var numImagenes = 11; //aumentar o disminuir según el total de los arrays siguientes


// array con las imágenes a mostrar-> 10 posiciones
var imagenes = [
"imagenes/cabecera/rio.gif",
"imagenes/cabecera/adamuz.gif",
"imagenes/cabecera/bujalance.gif",
"imagenes/cabecera/canete.gif",
"imagenes/cabecera/elcarpio.gif",
"imagenes/cabecera/montoro.gif",
"imagenes/cabecera/pedroabad.gif",
"imagenes/cabecera/villadelrio.gif",
"imagenes/cabecera/villafranca.gif",
"imagenes/cabecera/comarca.gif",
"imagenes/cabecera/comarcasat.gif"];


// array con los textos alusivos a las imágenes -> 10 posiciones
var textos = [
"Portal del Centro Comercial Abierto Alto Guadalquivir",
"Página Web Oficial de Adamuz",
"Página Web Oficial de Bujalance",
"Página Web Oficial de Cañete de las Torres",
"Página Web Oficial de El Carpio",
"Página Web Oficial de Montoro",
"Página Web Oficial de Pedro Abad",
"Página Web Oficial de Villa del Río",
"Página Web Oficial de Villafranca de Córdoba",
"Página Web Oficial de la Mancomunidad de Municipios del Alto Guadalquivir",
"Portal de Noticias de la Comarca del Alto Guadalquivir "];



// array con los enlaces asociados a cada imagen
var enlaces = [
"inicio.php",
"http://www.adamuz.es/",
"http://www.bujalance.es/",
"http://www.aytocanetedelastorres.es/",
"http://www.ayunelcarpio.es/",
"http://montoro.es:8080/opencms/opencms/PortalWeb/inicio.html",
"http://www.aytopedroabad.com/index3.php",
"http://www.villadelrio.org/index3.php",
"http://www.villafrancadecordoba.es/",
"http://www.altoguadalquivir.com/",
"http://www.altoguadalquiviralminuto.com/"];


function cambiarImagen() {
   document.getElementById("enlaceImagen").href=enlaces[imagenAMostrar];
   document.getElementById("enlaceImagen").title="Enlace a " + textos[imagenAMostrar];
   document.getElementById("imagenCabecera").src=imagenes[imagenAMostrar];
   document.getElementById("imagenCabecera").alt=textos[imagenAMostrar];
   if (++imagenAMostrar>=numImagenes) { imagenAMostrar=0 };
}