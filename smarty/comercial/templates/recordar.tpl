    <div id="formRecordar">
       <form action="{$action}" method="post">
           <div class="formulario">

     	  	<label for="F6_C1" {$class_F6_C1|default:""}>Su Correo Electr&oacute;nico</label>
	     	<input type="text" name="F6_C1" id="F6_C1" value="{$F6_C1|default:""}"/>

		<input class="boton" type="submit" name="enviar" id="enviar" value="{$enviar|default:""}"/>

  	        <div class="clear"></div>

           </div>
       </form>
    </div>
