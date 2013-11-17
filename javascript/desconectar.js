function inicializarDesconectar() {
         petDesconectar = new net.CargadorContenidos("area-personal/desconectar.php"+SID,trataRespDesconectar,"POST",null,true);
}

function trataRespDesconectar() {
	
//si encuentra alg�n caracter que no sea los indicados (los que permit� en la aceptacion de nombre y apellidos m�s los propios de la notacion
//json, dice que hay un error. Esto es una simple proteccion a la ejecuci�n de c�digo maligno con "eval". Me aseguro que no se pierde nada
//de mi respuesta, pues yo no he usado m�s que esos caracteres y por otro lado, con ese conjunto no creo que se pueda crear c�digo javascript
//ya que elementos b�sicos como el signo "=" no est�n permitidos y las llaves s�lo se pueden usar al principio y al final...
//se podr�a hacer m�s fuerte haciendo un chequeo exhaustivo de que la cadena tenga notaci�n json, pero pienso que con esto me protejo lo suficiente.
//search(/[^a-zA-Z0-9����������������\-\�\�\/\:\{\}\"\,\s\.]/) //s�lo busca, con el match evito que haya llaves entremedias.

//adem�s, cuando no se llama correctamente, desconectar env�a el c�digo HTML que mostrar�a en el caso normal, lo que queremos evitar que aparezca.

        if (petDesconectar.req.responseText.match(/^(\s)*(\{){1}([a-zA-Z0-9����������������\-\�\�\/\:\"\,\&\;\s\.])+(\}{1})$/)) {
	        respJson = eval("("+petDesconectar.req.responseText+")");
	        if (respJson["resultado"]=="ok") {

			 alert("Ha sido desconectado. Hasta pronto!");

		       	 c = document.getElementById("menuConexion");
			 l = document.getElementById("links_bienvenido");
		       	 c.removeChild(l);

			 d = document.createElement("div");
			 d.id="formulario";

			 f = document.createElement("form");
			 f.action="inicio.php"+SID;
			 f.method = "post";

			 i = document.createElement("input");
			 i.type = "text";
			 i.name = "usuariopp";
			 i.id = "usuariopp";
			 i.value="Usuario";
			 f.appendChild(i);

			 i = document.createElement("input");
			 i.type = "password";
			 i.name = "passwordpp";
			 i.id = "passwordpp";
			 i.value="Password";
			 f.appendChild(i);

			 d.appendChild(f);
			 c.appendChild(d);

		         d = document.createElement("div");
			 d.id="links";
			 
			 a = document.createElement("a");
			 a.id="registrar";
			 a.href="area-personal/registro.php"+SID;
			 t = document.createTextNode("Registrarse");
		         a.appendChild(t);
			 d.appendChild(a);

			 a = document.createElement("a");
			 a.id="olvido";
			 a.href = "area-personal/recordar.php"+SID;
			 t = document.createTextNode("Recordar Datos");
		         a.appendChild(t);
			 d.appendChild(a);

			 a = document.createElement("a");
			 a.id="conectar";
			 a.href = "area-personal/conectar.php"+SID;
			 t = document.createTextNode("Conectar");
		         a.appendChild(t);
			 d.appendChild(a);

			 c.appendChild(d);

			 inicializarConectar();

			 document.getElementById("usuariopp").value="Usuario";
	  		 document.getElementById("passwordpp").value="Password";
	  		 document.getElementById("usuariopp").onfocus=borrarEtiquetaInput;
	  		 document.getElementById("passwordpp").onfocus=borrarEtiquetaInput;
	  		 document.getElementById("conectar").onclick=autentificarUsuario;



		} else {
                        alert(respJson["mensaje"]);
  		}
        } else {
	   	alert("No se ha podido realizar la desconexi�n. Int�ntelo m�s tarde");
        }
}

function desconectarUsuario() {
         parametros = [["soloChequear", "ok"]];
         petDesconectar.cargaContenido(parametros);
         return false;
}


