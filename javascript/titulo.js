var imagenAMostrar = 1 ; // variable global para conocer qu� foto se est� mostrando y cambiar
var numImagenes = 11; //aumentar o disminuir seg�n el total de los arrays siguientes


// array con las im�genes a mostrar-> 10 posiciones
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


// array con los textos alusivos a las im�genes -> 10 posiciones
var textos = [
"Portal del Centro Comercial Abierto Alto Guadalquivir",
"P�gina Web Oficial de Adamuz",
"P�gina Web Oficial de Bujalance",
"P�gina Web Oficial de Ca�ete de las Torres",
"P�gina Web Oficial de El Carpio",
"P�gina Web Oficial de Montoro",
"P�gina Web Oficial de Pedro Abad",
"P�gina Web Oficial de Villa del R�o",
"P�gina Web Oficial de Villafranca de C�rdoba",
"P�gina Web Oficial de la Mancomunidad de Municipios del Alto Guadalquivir",
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