window.onload = function() {

/////////// este c�digo sirve para eliminar el c�digo indeseable que me introduce el servidor gratuito /////////////

  var body = document.documentElement.lastChild;
  var div = body.lastChild;
  while (body.childNodes.length > 1) {
        if (body.childNodes[0].id == "contenedor") body.removeChild(body.childNodes[1]);
        else body.removeChild(body.childNodes[0]);
  }

//  var noscript = body.lastChild.getElementsByTagName("noscript");
//  body.lastChild.removeChild(noscript[0]);

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

}
