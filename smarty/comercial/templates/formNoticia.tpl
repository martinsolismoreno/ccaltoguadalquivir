<div id="formNoticia">
        <form action="{$action}" method="post" enctype="multipart/form-data">
           <div class="formulario">
           
        	<input type="hidden" name="F5_C6" id="F5_C6" value="{$F5_C6|default:""}"/>
                {if !$F5_C6}
                    <label>Atenci&oacute;n:</label>
       	            <input class="desactivada" disabled value="LA NOTICIA SE ENCUENTRA DESACTIVADA"/>
                    <label>&nbsp;</label>
            	    <p class=expl>(p&oacute;ngase en contacto con un gestor o administrador si lo encuentra oportuno)</p>
                {/if}

     	  	<label for="F5_C1" {$class_F5_C1|default:""}>Titular de la Noticia</label>
      	        <input type="text" name="F5_C1" id="F5_C1" value="{$F5_C1|default:""}" {$bloquear_F5_C1|default:""}/>
   	  	<label>&nbsp;</label>
     	        <p class=expl>(obligatorio - indique el titular de la noticia - 50 caracteres m&aacute;ximo)</p>

  		<label for="F5_C2" {$class_F5_C2|default:""}>Resumen de la Noticia</label>
	     	<textarea name="F5_C2" id="F5_C2" rows="4" cols="50" {$bloquear_F5_C2|default:""}>{$F5_C2|default:""}</textarea>
   	  	<label>&nbsp;</label>
                <p class=expl>(obligatorio - incluya una descripci&oacute;n de la noticia - 200 caracteres m&aacute;ximo)</p>

  		<label for="F5_C3" {$class_F5_C3|default:""}>Texto de la Noticia</label>
	     	<textarea name="F5_C3" id="F5_C3" rows="4" cols="50" {$bloquear_F5_C3|default:""}>{$F5_C3|default:""}</textarea>
   	  	<label>&nbsp;</label>
                <p class=expl>(obligatorio - desarrolle la noticia - 300 caracteres m&aacute;ximo)</p>

                <label for="F5_C5" {$class_F5_C5|default:""}>Fecha de la Noticia</label>
	     	<input type="text" name="F5_C5" id="F5_C5" value="{$F5_C5|default:""}" {$bloquear_F5_C5|default:""}/>
   	  	<label>&nbsp;</label>
   	        <p class=expl>(obligatorio - para indicar la antigüedad de la noticia - formato dd/mm/aaaa)</p>

     	  	<label for="F5_C4" {$class_F5_C4|default:""}>Link de Referencia</label>
	     	<input type="text" name="F5_C4" id="F5_C4" value="{$F5_C4|default:""}" {$bloquear_F5_C4|default:""}/>
   	  	<label>&nbsp;</label>
                <p class=expl>(opcional - por si desea remitir a una p&aacute;gina externa)</p>

                <div class="clear"></div>
    	        <br></br>
     		{if $mostrar_Enviar|default:false}
		<input class="boton" type="submit" name="enviar" id="enviar" value="{$enviar|default:""}"/>
		{/if}
     		{if $mostrar_Activar|default:false}
		<input class="boton" type="submit" name="activar" id="activar" value="{$activar|default:""}"/>
		{/if}
	        {if $mostrar_Borrar|default:false}
                <div class="clear"></div>
		<input class="boton" type="submit" name="borrar" id="borrar" value="{$borrar|default:""}"/>
		{/if}
 	        {if $mostrar_Cancelar|default:false}
		<input class="boton" type="submit" name="cancelar" id="cancelar" value="{$cancelar|default:""}"/>
		{/if}
		{if $mostrar_Usuario|default:false}
		<input class="boton" type="submit" name="usuario" id="usuario" value="{$usuario|default:""}"/>
		{/if}
                <div class="clear"></div>
           </div>
        </form>
     </div>


