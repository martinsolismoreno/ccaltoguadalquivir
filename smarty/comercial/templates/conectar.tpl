    <div id="formConectar">
       <form action="{$action}" method="post">
           <div class="formulario">

     	  	<label for="F0_C1" {$class_F0_C1|default:""}>Usuario</label>
	     	<input type="text" name="F0_C1" id="F0_C1" value="{$F0_C1|default:""}"/>

     	  	<label for="F0_C2" {$class_F0_C2|default:""}>Contrase&ntilde;a</label>
	     	<input type="password" name="F0_C2" id="F0_C2" value="{$F0_C2|default:""}"/>

		<input class="boton" type="submit" name="enviar" id="enviar" value="{$enviar|default:""}"/>

  	        <div class="clear"></div>

           </div>
       </form>
    </div>
