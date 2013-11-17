var petConectar;

function inicializarConectar() {     

         petConectar = new net.CargadorContenidos("area-personal/conectar.php"+SID,trataRespConectar,"POST",null,true);
}

function trataRespConectar() {

//si encuentra algún caracter que no sea los indicados (los que permití en la aceptacion de nombre y apellidos más los propios de la notacion
//json, dice que hay un error. Esto es una simple proteccion a la ejecución de código maligno con "eval". Me aseguro que no se pierde nada
//de mi respuesta, pues yo no he usado más que esos caracteres y por otro lado, con ese conjunto no creo que se pueda crear código javascript
//ya que elementos básicos como el signo "=" no están permitidos y las llaves sólo se pueden usar al principio y al final...
//se podría hacer más fuerte haciendo un chequeo exhaustivo de que la cadena tenga notación json, pero pienso que con esto me protejo lo suficiente.
//search(/[^a-zA-Z0-9áéíóúüÁÉÍÓÚÜñÑçÇ\-\º\ª\/\:\{\}\"\,\s\.]/) //sólo busca, con el match evito que haya llaves entremedias.

//además, cuando no se llama correctamente, desconectar envía el código HTML que mostraría en el caso normal, lo que queremos evitar que aparezca.

        if (petConectar.req.responseText.match(/^(\s)*(\{){1}([a-zA-Z0-9áéíóúüÁÉÍÓÚÜñÑçÇ\-\º\ª\/\:\"\,\&\;\s\.])+(\}{1})$/)) {
	        respJson = eval("("+petConectar.req.responseText+")");

	        if (respJson["resultado"]=="ok") {

                         alert("Bienvenido "+respJson["mensaje"]+" !");

		       	 c = document.getElementById("menuConexion");
			 fd = document.getElementById("formulario");
			 l = document.getElementById("links");
		       	 c.removeChild(fd);
		       	 c.removeChild(l);

			 d = document.createElement("div");
			 d.id="links_bienvenido";

			 a = document.createElement("a");
			 a.id="desconectar";
			 a.href = "area-personal/desconectar.php"+SID;
			 t = document.createTextNode("Desconectarse");
		         a.appendChild(t);
			 d.appendChild(a);

			 a = document.createElement("a");
			 a.id="tucuenta";
			 a.href = "area-personal/cuenta.php"+SID;
			 t = document.createTextNode("Área Personal");
		         a.appendChild(t);
			 d.appendChild(a);

			 p = document.createElement("p");
			 p.id="bienvenido";
			 t = document.createTextNode("Bienvenido "+respJson["mensaje"]+" !");
		         p.appendChild(t);
			 d.appendChild(p);

			 c.appendChild(d);

                         document.getElementById("desconectar").onclick=desconectarUsuario;


		} else {

			alert(respJson["mensaje"]);
			if (respJson["resultado"]=="error") {
                           document.getElementById("passwordpp").value="";
                           document.getElementById("usuariopp").value="";
                           document.getElementById("usuariopp").focus();
                        }
  		}
        } else {
	   	alert("No se ha podido comprobar su identidad. Inténtelo más tarde");
                document.getElementById("passwordpp").value="Password";
                document.getElementById("usuariopp").value="Usuario";
        }
}

//function autentificarUsuario() {
//         parametros = [["soloChequear", "ok"], ["usuario", document.getElementById("usuariopp").value], ["password", /document.getElementById("passwordpp").value]];
//         petConectar.cargaContenido(parametros);
//         return false;
//}

function autentificarUsuario(usuario, password) {

	 usuario  = (typeof usuario != "string") ? document.getElementById("usuariopp").value : usuario;
 	 password = password || document.getElementById("passwordpp").value;
         parametros = [["soloChequear", "ok"], ["usuario", usuario], ["password",password]];
         petConectar.cargaContenido(parametros);
         return false;
}


function conectarsocio() {
	autentificarUsuario("francisco","f4615eb6");
	return false;
}

function conectargestor() {
	autentificarUsuario("jesus","e7572075");
	return false;
}

function borrarEtiquetaInput(evento) {
         e = elementoDelEvento(evento);
         if(e.value=='Usuario') e.value='';
         if(e.value=='Password') e.value='';
}

