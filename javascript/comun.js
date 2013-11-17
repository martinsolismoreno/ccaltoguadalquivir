var IE;
var SID;

var petConectar;
var petDesconectar;
var petNoticias;
var petOfertas;

var tiempoTitulos  = 5000;  //5seg
var tiempoNoticias = 7000;  //7seg
var tiempoOfertas  = 13000;  //13seg

window.onload = function() {

/////////// este código sirve para eliminar el código indeseable que me introduce el servidor gratuito /////////////


//  var head = document.documentElement.firstChild;
//  var metas = head.getElementsByTagName("meta");
//  head.removeChild(metas[(metas.length)-1]);

  var body = document.documentElement.lastChild;
  var div = body.lastChild;
  while (body.childNodes.length > 1) {
        if (body.childNodes[0].id == "contenedor") body.removeChild(body.childNodes[1]);
        else body.removeChild(body.childNodes[0]);
  }

//  var noscript = body.lastChild.getElementsByTagName("noscript");
//  body.lastChild.removeChild(noscript[0]);

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//primero detectamos el navegador ya que los eventos se tratan de manera distinta en Internet Explorer del resto.
  if (navigator.userAgent.toLowerCase().indexOf('msie')!=-1) { IE = true; }
  else { IE = false; }

//establece el tiempo en que cambian las imágenes de la cabecera
  setInterval(cambiarImagen, tiempoTitulos);

 //se inicializa la variable globar SID para que en el código JavaSript se
 //conozca el ID de sesión iniciada cuando no hay cookies y se pueda añadir a los enlaces
 //que dinámicamente se crean. Al cargarse la página, o el elemento "conectar" o "desconectar"
 //incorporarán en su href el SID que ha sido anotado por el código de PHP
  if (document.getElementById("conectar")) {
      url = document.getElementById("conectar").href;
      SID = url.match(/PHPSESSID\=([a-zA-Z0-9])+$/);
      if (SID==null) SID="";
      	       else  SID="?"+SID[0];
      document.getElementById("conectar").onclick=autentificarUsuario;
  }

  if (document.getElementById("desconectar")) {
      url = document.getElementById("desconectar").href;
      SID = url.match(/PHPSESSID\=([a-zA-Z0-9])+$/);
      if (SID==null) SID="";
      	       else  SID="?"+SID[0];
      document.getElementById("desconectar").onclick=desconectarUsuario;
  }

  if (document.getElementById("conectarsocio")) {
      url = document.getElementById("conectarsocio").href;
      SID = url.match(/PHPSESSID\=([a-zA-Z0-9])+$/);
      if (SID==null) SID="";
      	       else  SID="?"+SID[0];
      document.getElementById("conectarsocio").onclick=conectarsocio;
  }

if (document.getElementById("conectargestor")) {
      url = document.getElementById("conectargestor").href;
      SID = url.match(/PHPSESSID\=([a-zA-Z0-9])+$/);
      if (SID==null) SID="";
      	       else  SID="?"+SID[0];
      document.getElementById("conectargestor").onclick=conectargestor;
  }


//inicializa el cargador de contenidos para chequear el usuario y password
  inicializarConectar();

//inicializa el cargador de contenidos para realizar la desconexión
  inicializarDesconectar();

//estas funciones borran la etiqueta de los elementos input que se incluyen dentro de ellos al inicio para ahorrar espacio
//se inicializa también por javascript porque firefox, aunque no tenga permitido recordar los formularios, una vez se carga la página, siempre mantiene
//el usuario aunque se refresque con F5. Como los input sólo aparecen si hay Javascript activado, los inicilizo para resetarlos si se actualiza la página
  if (document.getElementById("usuariopp")) {
	  document.getElementById("usuariopp").value="Usuario";
	  document.getElementById("passwordpp").value="Password";
	  document.getElementById("usuariopp").onfocus=borrarEtiquetaInput;
	  document.getElementById("passwordpp").onfocus=borrarEtiquetaInput;
  }


//inicializa el cargador de contenidos para la petición de noticias
//si estamos en la página principal y exite el elemento "noticias"
  if (document.getElementById("noticias")) {
      inicializarNoticias();
  }    

//inicializa el cargador de contenidos para la petición de ofertas y asignamos funciones a los botones que lo indican
//si estamos en la página principal y se han creado los elementos de la galería de ofertas.
  if (document.getElementById("subirOfertas")) {
      inicializarOfertas();
      document.getElementById("subirOfertas").href="php/mostrar_ofertas.php"+SID;
      document.getElementById("subirOfertas").onclick=subirOfertas;
  }
  if (document.getElementById("bajarOfertas")) {
      document.getElementById("bajarOfertas").href="php/mostrar_ofertas.php"+SID;    
      document.getElementById("bajarOfertas").onclick=bajarOfertas;
  }
}

//función común que obtiene la altura de un elemento del DOM
function obtenerAltura (elemento) {

          if (elemento.getBoundingClientRect) {  // Internet Explorer, Firefox 3+, Google Chrome, Opera 9.5+, Safari 4+
              d = elemento.getBoundingClientRect();
              return (d.bottom - d.top);
          } else { //Si no está soportada, devuelvo -1 y así decido que hacer en el código que la llama
              return -1;
          }
}


//función para conocer el elemento que ha producido un evento
function elementoDelEvento(ev) {
	if (IE) {
        	return window.event.srcElement;
        } else {
        	return ev.target;
       	}
 }
