function inicializarOfertas() {

         petOfertas = new net.CargadorContenidos("area-personal/galeria_ofertas.php"+SID,trataRespOferta,"POST",null,true,servidorOfertasNoDisponible);
         
         bucleOfertas = setInterval(bajarOfertas, tiempoOfertas);

}

function trataRespOferta() {

//hago un filtrado de los caracteres que mandan para controlar el "eval"
//aqu� tendr�a que pensar en una expresi�n regular xq pueden venir caracteres "peligrosos"...
//adem�s, cuando no se llama correctamente, desconectar env�a el c�digo HTML que mostrar�a en el caso normal, lo que queremos evitar que aparezca.


        respuesta = petOfertas.req.responseText.replace(/<div[^>]*>((.|\n|\r)*?)*?<\/div>$/,"");
        if (respuesta.match(/^(\s)*(\{){1}([a-zA-Z0-9\-\_\{\}\[\]\&\?\=\#\/\:\"\,\;\s\.])+(\}{1})$/)) {
	        respJson = eval("("+respuesta+")");
	        if (respJson["resultado"]=="ok") {
                    for(i=0;i<((respJson["imagenes"].length)-1);i++) {
			document.getElementById(("enlaceOferta"+(i+1))).href=respJson["imagenes"][i]["link"];
                        document.getElementById(("oferta"+(i+1))).src=respJson["imagenes"][i]["src"];
//                        oferta = document.getElementById(("ofertappal"+(i+1)));
//			a = document.getElementById(("enlaceOferta"+(i+1)));
//			oferta.removeChild(a);
//			a = document.createElement("a");
//	                a.id = "enlaceOferta"+(i+1);
//			a.href = respJson["imagenes"][i]["link"];
//			imagen = document.createElement("img");
//			imagen.src = respJson["imagenes"][i]["src"];
//		        imagen.title = "oferta"+(i+1);
//		        imagen.alt = "oferta"+(i+1);
//			a.appendChild(imagen);
//			oferta.appendChild(a);
                    }
                } //si resultado = error no hacemos nada
        } //y si la expresi�n JSON es inv�lida tampoco
}

function subirOfertas() {
         clearInterval(bucleOfertas);
         bucleOfertas = setInterval(bajarOfertas, tiempoOfertas);
         parametros = [["moverOfertas", "siguiente"]];
         petOfertas.cargaContenido(parametros);
         return false;
}

function bajarOfertas() {
         clearInterval(bucleOfertas);
         bucleOfertas = setInterval(bajarOfertas, tiempoOfertas);
         parametros = [["moverOfertas", "anterior"]];
         petOfertas.cargaContenido(parametros);
         return false;
}


function servidorOfertasNoDisponible() {

         clearInterval(bucleOfertas);
	 alert("El Servidor de Ofertas No est� Disponible");
	 for(i=1;i<4;i++) {
                     document.getElementById(("enlaceOferta"+i)).href="#";
                     document.getElementById(("oferta"+i)).src="imgUsuarios/ofertas/ofertaxdefecto"+i+".gif";
         }

}