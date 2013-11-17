// codigo para peticiones

var net = new Object();

net.READY_STATE_UNINITIALIZED=0;
net.READY_STATE_LOADING=1;
net.READY_STATE_LOADED=2;
net.READY_STATE_INTERACTIVE=3;
net.READY_STATE_COMPLETE=4;

// Constructor

net.CargadorContenidos = function(url, funcionOk, metodo, datos, modo, funcionError, funcionEstado) {
// url: direcci�n a d�nde hacer la petici�n (obligatoria)

// funcionOk: funci�n que tratar� la respuesta del servidor (obligatoria)

// metodo: tipo de peticion, GET o POST (por defecto se pone GET si no se rellena)

// datos: datos que se pasan a la petici�n, en formato un array de par�metros, con otro array de dos posiciones con cada par�metro y su valor 
//         [ [parametro1, valor1], [parametro2, valor2].... y as�...]

// modo: forma que se hace la petici�n, asincrona (true) por defecto si no se pone nada, y s�ncrona (false) si se especifica

// funcionError: funci�n a d�nde pasar el control en caso que se produzca un error. Si no se indica, hay una por defecto que
//         -> env�a un mensaje de aviso a la pantalla, pero no hace nada m�s

// funcionEstados: funci�n a d�nde pasar el control si se quiere tener un mayor control o realizar acciones antes de que la petici�n
//         -> finalice con un c�digo correcto. Tambi�n existe funci�n por defecto para ello.

  	this.req = null;
	this.url = url;
	this.onload = funcionOk;
	this.metodo  = (metodo) ? metodo : "GET";
	this.datos = (datos) ? datos+"&nocache="+Math.random() : null;
	this.modo  = (!modo) ? modo : true;
	this.onerror = (funcionError) ? funcionError : this.defaultError;
	this.onReadyState = (funcionEstado) ? funcionEstado : this.onReadyState;

}


net.CargadorContenidos.prototype = {

  	cargaContenido: function(datosNuevos) {

              	datosLlamada = (datosNuevos) ? datosNuevos : this.datos;
                var datos = "";
                if (datosLlamada != null) {
                   for (i = 0; i<datosLlamada.length; i++) {
                       datos += encodeURIComponent(datosLlamada[i][0])+"="+encodeURIComponent(datosLlamada[i][1])+"&";
                   }
                    datos+="nocache="+Math.random();
                } else {
                    datos = null;
                }
		if(window.XMLHttpRequest) {
			this.req = new XMLHttpRequest();
    		} else if(window.ActiveXObject) {
			this.req = new ActiveXObject("Microsoft.XMLHTTP");
		}
	    	if(this.req) {
      			try {
				var loader = this;
    				this.req.onreadystatechange = function() {
					loader.onReadyState.call(loader);
				}
				if (this.metodo == "POST") {
 				        this.req.open(this.metodo, this.url, this.modo);
					this.req.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
					this.req.send(datos);
				} else {
                                        var urlnueva = this.url;
                                        if (datos!=null) urlnueva += datos
                                        this.req.open(this.metodo, urlnueva, this.modo);
                                        this.req.send(null);
                                }

		      	} catch(err) {
			        this.onerror.call(this);
			}
    		}
	},

	onReadyState: function() {
		var req = this.req;
		var ready = req.readyState;
		if(ready == net.READY_STATE_COMPLETE) {
			var httpStatus = req.status;
      			if(httpStatus == 200 || httpStatus == 0) {
				this.onload.call(this);
      			} else {
				this.onerror.call(this);
			}
    		}
  	},


  	defaultError: function() {
                if (this.req.status == 404) {
    		       alert("El recurso no se encuentra disponible");
                } else {
    		       alert("Se ha producido un error al obtener los datos" +
		       "\n\nreadyState:" + this.req.readyState +
   		       "\nstatus: " + this.req.status +
		       "\nheaders: " + this.req.getAllResponseHeaders());
                }
	}

}

//FIN CODIGO PETICIONES


