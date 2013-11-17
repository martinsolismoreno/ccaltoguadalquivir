     <div id="formRegistro">
        <form action="{$action}" method="post">
            <div class="formulario">

               {html_radios name="F1_C1" options=$opciones_F1_C1 selected=$F1_C1}
	       <div class="clear"></div>
	       <input class="boton" type="submit" name="enviar" id="enviar" value="{$enviar|default:""}"/>

           </div>
        </form>
     </div>

