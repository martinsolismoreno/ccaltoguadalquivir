<div id="formEmpresa">
        <form action="{$action}" method="post" enctype="multipart/form-data">
           <div class="formulario">

     	  	<label for="F3_C1" {$class_F3_C1|default:""}>D.N.I.</label>
      	        <input type="text" name="F3_C1" id="F3_C1" value="{$F3_C1|default:""}" {$bloquear_F3_C1|default:""}/>
      	  	<label>&nbsp;</label>
     	        <p class="expl">(obligatorio - incluya su documento nacional de identidad - formato 99999999A)</p>


     	  	<label for="F3_C2" {$class_F3_C2|default:""}>Nombre de la Empresa</label>
	     	<input type="text" name="F3_C2" id="F3_C2" value="{$F3_C2|default:""}" {$bloquear_F3_C2|default:""}/>
      	  	<label>&nbsp;</label>
     	        <p class="expl">(obligatorio - indique el nombre comercial de su empresa - 50 car. m&aacute;x.)</p>


	     	<label for="F3_C3" {$class_F3_C3|default:""}>Sector al que pertenece</label>
		<select name="F3_C3" id="F3_C3" size="1" {$bloquear_F3_C3|default:""}>
                   {html_options options=$opciones_F3_C3|default:"" selected=$F3_C3|default:""}
                </select>
      	  	<label>&nbsp;</label>
     	        <p class="expl">(obligatorio - elija el sector al que pertece su empresa)</p>
     	        <p class="expl">(si considera necesario incluir uno nuevo, p&oacute;ngase en contacto con un gestor)</p>
     	        <p class="expl">&nbsp;</p>


  		<label for="F3_C4" {$class_F3_C4|default:""}>Descripci&oacute;n de la Empresa</label>
	     	<textarea name="F3_C4" id="F3_C4" rows="4" cols="50" {$bloquear_F3_C4|default:""}>{$F3_C4|default:""}</textarea>
     	        <p class="expl">(obligatorio - describa y anuncie los servicios que ofrece - 300 car. m&aacute;x.)</p>
     	        <p class="expl">&nbsp;</p>


  		<label for="F3_C5" {$class_F3_C5|default:""}>Localizaci&oacute;n de la Empresa</label>
	     	<textarea name="F3_C5" id="F3_C5" rows="4" cols="50" {$bloquear_F3_C5|default:""}>{$F3_C5|default:""}</textarea>
      	  	<label>&nbsp;</label>
     	        <p class="expl">(obligatorio - describa la ubicaci&oacute;n f&iacute;sica de su empresa </p>
                <p class="expl">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;de la forma m&aacute;s oportuna para su f&aacute;cil localizaci&oacute;n - 200 car. m&aacute;x.)</p>
     	        <p class="expl">&nbsp;</p>


  		<label for="F3_C6" {$class_F3_C6|default:""}>Pedan&iacute;a</label>
	     	<input type="text" name="F3_C6" id="F3_C6" value="{$F3_C6|default:""}" {$bloquear_F3_C6|default:""}/>
      	  	<label>&nbsp;</label>
     	        <p class="expl">(opcional - indique si se encuentra en alguna pedan&iacute;a de nuestra comarca) </p>


	     	<label for="F3_C7" {$class_F3_C7|default:""}>Poblaci&oacute;n</label>
		<select name="F3_C7" id="F3_C7" size="1" {$bloquear_F3_C7|default:""}>
                   {html_options options=$opciones_F3_C7|default:"" selected=$F3_C7|default:""}
                </select>
      	  	<label>&nbsp;</label>
     	        <p class="expl">(obligatorio - elija la poblaci&oacute;n donde se encuentra)</p>


  		<label for="F3_C8" {$class_F3_C8|default:""}>Tel&eacute;fono 1</label>
	     	<input type="text" name="F3_C8" id="F3_C8" value="{$F3_C8|default:""}" {$bloquear_F3_C8|default:""}/>
      	  	<label>&nbsp;</label>
     	        <p class="expl">(obligatorio - indique por lo menos un n&uacute;mero de contacto con su empresa)</p>

  		<label for="F3_C9" {$class_F3_C9|default:""}>Tel&eacute;fono 2</label>
	     	<input type="text" name="F3_C9" id="F3_C9" value="{$F3_C9|default:""}" {$bloquear_F3_C9|default:""}/>
      	  	<label>&nbsp;</label>
     	        <p class="expl">(opcional - el formato debe ajustarse a (999) 999 999 / 999-999-999 o similar)</p>

  		<label for="F3_C10" {$class_F3_C10|default:""}>Fax</label>
	     	<input type="text" name="F3_C10" id="F3_C10" value="{$F3_C10|default:""}" {$bloquear_F3_C10|default:""}/>
      	  	<label>&nbsp;</label>
     	        <p class="expl">(opcional - fax de contacto - formato similar al n&uacute;mero de tel&eacute;fono)</p>

  		<label for="F3_C11" {$class_F3_C11|default:""}>Correo Electr&oacute;nico</label>
	     	<input type="text" name="F3_C11" id="F3_C11" value="{$F3_C11|default:""}" {$bloquear_F3_C11|default:""}/>
      	  	<label>&nbsp;</label>
     	        <p class="expl">(opcional - indique una direcci&oacute;n de correo de atenci&oacute;n a sus clientes)</p>

  		<label for="F3_C12" {$class_F3_C12|default:""}>P&aacute;gina Web</label>
	     	<input type="text" name="F3_C12" id="F3_C12" value="{$F3_C12|default:""}" {$bloquear_F3_C12|default:""}/>
      	  	<label>&nbsp;</label>
     	        <p class="expl">(opcional - si tiene web de su empresa, indique la direcci&oacute;n)</p>

  		<label for="F3_C13" {$class_F3_C13|default:""}>Horario de Atenci&oacute;n al P&uacute;blico</label>
	     	<input type="text" name="F3_C13" id="F3_C13" value="{$F3_C13|default:""}" {$bloquear_F3_C13|default:""}/>
     	        <p class="expl">(obligatorio - indique horario de apertura o atenci&oacute;n por otro medio de contacto)</p>
     	        <p class="expl">&nbsp;</p>

                {if $imagen!=""|default:""}
     	  	<label>Imagen Corporativa</label>
   	        <img src="{$imagen}" alt="Logotipo de la Empresa"/>
		{/if}
     		{if $mostrar_imgEmpresa|default:false}
   	  	<label for="imgEmpresa" {$class_imgEmpresa}>Subir Imagen Corporativa</label>
	     	<input type="hidden" name="imgEmpresa" id="imgEmpresa" value="{$imgEmpresa|default:"" }"/>
	     	<input type="file" name="archEmpresa" id="archEmpresa"/>
     	        <p class="expl">(recomendable - suba un archivo con el logo o imagen corporativa de empresa)</p>
     	        <p class="expl">(debe tener formato gif o jpeg y unas dimensiones de 170x170 p&iacute;xeles)</p>
	     	{/if}

                <div class="clear"></div>
    	        <br></br>
     		{if $mostrar_Enviar|default:false}
		<input class="boton" type="submit" name="enviar" id="enviar" value="{$enviar|default:""}"/>
		{/if}
	        {if $mostrar_Vista|default:false}
		<input class="boton" type="submit" name="vista" id="vista" value="{$vista|default:""}"/>
		{/if}
		{if $mostrar_Usuario|default:false}
		<input class="boton" type="submit" name="usuario" id="usuario" value="{$usuario|default:""}"/>
		{/if}
 	        {if $mostrar_Ofertas|default:false}
		<input class="boton" type="submit" name="ofertas" id="ofertas" value="{$ofertas|default:""}"/>
		{/if}
                <div class="clear"></div>
           </div>
        </form>
     </div>


