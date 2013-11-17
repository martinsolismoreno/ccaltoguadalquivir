var altura; //lleva la cuenta de la altura en píxeles que ocupan las noticias mostradas hasta ahora
var alturaMax; //se establece un  alto como espacio máximo para las noticias, así no descuadra la columna de ofertas..
var margen; //número de píxeles de margen que dejo entre noticia y noticia
var noticias; //aquí se guarda como variable global las noticias recuperadas
var numNoticias; //para llevar el control de noticias que se muestran cuando no se tienen en cuenta el tamaño
var noticiasMax; //número máximo de noticias que se pueden mostrar cuando falla el calculo del tamaño del div


function inicializarNoticias() {

         petNoticias = new net.CargadorContenidos("area-personal/galeria_noticias.php"+SID,trataRespNoticia,"POST",null,true,servidorNoticiasNoDisponible);

         bucleNoticias = setInterval(nuevaNoticia, tiempoNoticias);

         recuperarNoticias();

         altura = 0;
         alturaMax=350;
         margen=5;
	 numNoticias = 0;
	 noticiasMax = 3;


}

function recuperarNoticias() {

         parametros = [["ultimasNoticias", ""]];
         petNoticias.cargaContenido(parametros);

}

function nuevaNoticia() {

         parametros = [["nuevaNoticia", ""]];
         petNoticias.cargaContenido(parametros);
}


function trataRespNoticia() {

//         var respuesta = petNoticias.req.responseText.replace(/<div[^>]*>((.|\n|\r)*?)*?<\/div>$/,"");
         var respuesta = petNoticias.req.responseText.split("<div");
//	 expr = /<br/g
//       if (expr.test(respuesta)) { alert("ojo br"); alert(respuesta);}
//	 expr = /\(\)/g
//       if (expr.test(respuesta)) { alert("ojo parentesis"); alert(respuesta);}

         respJson = eval("("+respuesta[0]+")");
         if (respJson["resultado"]=="ok") {
             noticias = respJson["noticias"];
             cargaNoticias();
         }
}


function cargaNoticias() {

   clearInterval(bucleNoticias);
   borrarNoticias();
   for(i=noticias.length;i>0;i--) mostrarNoticia(noticias[i-1],i-1,"parcial");
   bucleNoticias = setInterval(nuevaNoticia, tiempoNoticias);
   return false;

}

function mostrarNoticia(noticia, posicion, modo) {
  
         nodoNoticia=document.getElementById("listaNoticias")

         d = document.createElement("div");
         d.className = "noticia";

	 h4 = document.createElement("h4");
	 a = document.createElement("a");
	 if (noticia["numero"]>0) a.href = noticia["mas"]
	 else a.href="#";
         a.innerHTML=noticia["titular"];
	 h4.appendChild(a);
	 d.appendChild(h4);

	 p = document.createElement("p");
	 p.className = "info";
	 s = document.createElement("span");
	 t = document.createTextNode(noticia["fecha"]);
	 s.appendChild(t);
	 s.className = "fecha";
	 p.appendChild(s);
	 d.appendChild(p);

	 p=document.createElement("p");
	 p.className = "resumen";
         p.innerHTML=noticia["resumen"];
	 d.appendChild(p);

         if ((modo=="parcial")&&(noticia["numero"]>0)) {
  	     a = document.createElement("a");
	     a.href = noticia["mas"];
	     a.className="mas";
	     t = document.createTextNode("...+");
	     a.id=posicion;
	     a.onclick=noticiaCompleta;
             a.appendChild(t);
	     d.appendChild(a);
         } else {
             br=document.createElement("br");
             d.appendChild(br);
             br=document.createElement("br");
             d.appendChild(br);
    	     p=document.createElement("p");
	     p.className = "texto";
             p.innerHTML=noticia["texto"];
	     d.appendChild(p);
	     if (noticia["link"]!="") {
   	        a=document.createElement("a");
	        a.className = "link";
	        a.href=noticia["link"];
                if (noticia["numero"]>0) t = document.createTextNode("Más Información");
                else t = document.createTextNode("Enviar Noticia");
	        a.appendChild(t);
	        d.appendChild(a);
	     }
	     if (noticia["numero"]>0) {
  	         a = document.createElement("a");
	         a.href = "#";
	         a.className="volver";
	         t = document.createTextNode("Volver");
	         a.id=posicion;
                 a.onclick=cargaNoticias;
                 a.appendChild(t);
          	 d.appendChild(a);
             }
         }

         nodos = nodoNoticia.getElementsByTagName("div");
         if (nodos.length > 0) nodoNoticia.insertBefore(d,nodos[0]);
                          else nodoNoticia.appendChild(d);
	 var h = obtenerAltura(d);
	 numNoticias++;
	 if (h == -1) {
	     if (numNoticias > noticiasMax) {	
                     nodoNoticia.removeChild(nodos[nodos.length-1]);
	             numNoticias--;		
             }
	 } else {	
	     while ((altura + h + margen) > alturaMax) {
                     altura -= obtenerAltura(nodos[nodos.length-1]);
                     nodoNoticia.removeChild(nodos[nodos.length-1]);
             }
             altura += h;
         }


}

function borrarNoticias() {

        nodoNoticia=document.getElementById("listaNoticias")
        nodos = nodoNoticia.getElementsByTagName("div");
        while (nodos.length > 0) nodoNoticia.removeChild(nodos[nodos.length-1]);
        altura = 0;
	numNoticias = 0;

}

function noticiaCompleta(evento) {

         clearInterval(bucleNoticias);
         e = elementoDelEvento(evento);
         borrarNoticias();
         mostrarNoticia(noticias[e.id],e.id,"completa");
         return false;

}

function servidorNoticiasNoDisponible() {

         clearInterval(bucleNoticias);
         alert("Servidor de Noticias No Disponible");

}
