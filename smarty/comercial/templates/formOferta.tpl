<div id="formOferta">
        <form action="{$action}" method="post" enctype="multipart/form-data">
           <div class="formulario">
           
        	<input type="hidden" name="F4_C6" id="F4_C6" value="{$F4_C6|default:""}"/>
                {if !$F4_C6}
                    <label>Atenci&oacute;n:</label>
      	            <input class="desactivada" disabled value="LA OFERTA SE ENCUENTRA DESACTIVADA"/>
                    <label>&nbsp;</label>
            	    <p class=expl>(p&oacute;ngase en contacto con un gestor o administrador si lo encuentra oportuno)</p>
                {/if}

     	  	<label for="F4_C1" {$class_F4_C1|default:""}>Titular de la Oferta</label>
      	        <input type="text" name="F4_C1" id="F4_C1" value="{$F4_C1|default:""}" {$bloquear_F4_C1|default:""}/>
   	  	<label>&nbsp;</label>
     	        <p class=expl>(obligatorio - titular/reclamo de su oferta - 50 caracteres m&aacute;ximo)</p>

  		<label for="F4_C2" {$class_F4_C2|default:""}>Descripci&oacute;n de la Oferta</label>
	     	<textarea name="F4_C2" id="F4_C2" rows="4" cols="50" {$bloquear_F4_C2|default:""}>{$F4_C2|default:""}</textarea>
   	  	<label>&nbsp;</label>
     	        <p class=expl>(obligatorio - descripci&oacute;n detallada de su oferta - 300 caracteres m&aacute;ximo)</p>

  		<label for="F4_C3" {$class_F4_C3|default:""}>Condiciones de la Oferta</label>
	     	<textarea name="F4_C3" id="F4_C3" rows="4" cols="50" {$bloquear_F4_C3|default:""}>{$F4_C3|default:""}</textarea>
  	  	<label>&nbsp;</label>
     	        <p class=expl>(obligatorio - condiciones para beneficiarse de su oferta - 300 car. m&aacute;x.)</p>

     	  	<label for="F4_C4" {$class_F4_C4|default:""}>Fecha de Inicio</label>
	     	<input type="text" name="F4_C4" id="F4_C4" value="{$F4_C4|default:""}" {$bloquear_F4_C4|default:""}/>
  	  	<label>&nbsp;</label>
     	        <p class=expl>(opcional - fecha de comienzo de vigencia de la oferta - formato dd/mm/aaaa)</p>
   	        <p class=expl>(sin rellenar, la oferta no aparecerá en nuestra web, se mantiene oculta)</p>
    	        <p class=expl>&nbsp;</p>

  	        <label for="F4_C5" {$class_F4_C5|default:""}>Fecha de Finalizaci&oacute;n</label>
	     	<input type="text" name="F4_C5" id="F4_C5" value="{$F4_C5|default:""}" {$bloquear_F4_C5|default:""}/>
  	  	<label>&nbsp;</label>
     	        <p class=expl>(opcional - fecha del fin de vigencia de la oferta - formato dd/mm/aaaa)</p>
   	        <p class=expl>(sin rellenar, se entiende que la vigencia es indefinida)</p>
      	        <p class=expl>&nbsp;</p>

     	  	{if $imagen!=""|default:""}
     	  	<label>Imagen de la Oferta</label>
   	        <img src="{$imagen}" alt="Imagen de la Oferta"/>
		{/if}
     		{if $mostrar_imgOferta|default:false}
   	  	<label for="imgOferta" {$class_imgOferta}>Subir Imagen</label>
	     	<input type="hidden" name="imgOferta" id="imgOferta" value="{$imgOferta|default:"" }"/>
	     	<input type="file" name="archOferta" id="archOferta"/>
     	        <p class=expl>(opcional - incluya una imagen si desea anunciar su oferta en nuestra portada)</p>
     	        <p class=expl>(formato jpeg o gif -recomendable gif din&aacute;mico- y dimensiones de 300x250 px.)</p>


	     	{/if}

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
		{if $mostrar_Empresa|default:false}
		<input class="boton" type="submit" name="empresa" id="empresa" value="{$empresa|default:""}"/>
		{/if}
                <div class="clear"></div>
           </div>
        </form>
     </div>


