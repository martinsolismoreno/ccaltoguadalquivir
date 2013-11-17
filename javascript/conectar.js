var petConectar;

function inicializarConectar() {     

         petConectar = new net.CargadorContenidos("area-personal/conectar.php"+SID,trataRespConectar,"POST",null,true);
}

function trataRespConectar() {

//si encuentra alg�n caracter que no sea los indicados (los que permit� en la aceptacion de nombre y apellidos m�s los propios de la notacion
//json, dice que hay un error. Esto es una simple proteccion a la ejecuci�n de c�digo maligno con "eval". Me aseguro que no se pierde nada
//de mi respuesta, pues yo no he usado m�s que esos caracteres y por otro lado, con ese conjunto no creo que se pueda crear c�digo javascript
//ya que elementos b�sicos como el signo "=" no est�n permitidos y las llaves s�lo se pueden usar al principio y al final...
//se podr�a hacer m�s fuerte haciendo un chequeo exhaustivo de que la cadena tenga notaci�n json, pero pienso que con esto me protejo lo suficiente.
//search(/[^a-zA-Z0-9����������������\-\�\�\/\:\{\}\"\,\s\.]/) //s�lo busca, con el match evito que haya llaves entremedias.

//adem�s, cuando no se llama correctamente, desconectar env�a el c�digo HTML que mostrar�a en el caso normal, lo que queremos evitar que aparezca.

        if (petConectar.req.responseText.match(/^(\s)*(\{){1}([a-zA-Z0-9����������������\-\�\�\/\:\"\,\&\;\s\.])+(\}{1})$/)) {
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
			 t = document.createTextNode("�rea Personal");
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
	   	alert("No se ha podido comprobar su identidad. Int�ntelo m�s tarde");
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

