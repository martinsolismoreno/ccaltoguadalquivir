     <div id="formUsuario">
        <form action="{$action}" method="post">
           <div class="formulario">

     	  	<label for="F2_C1" {$class_F2_C1|default:""}>Usuario</label>
	     	<input type="text" name="F2_C1" id="F2_C1" value="{$F2_C1|default:""}" {$bloquear_F2_C1|default:""}/>
      	  	<label>&nbsp;</label>
     	        <p class="expl">(obligatorio - nombre con el que identificarse - letras, n&uacute;m, puntos y/o guiones)</p>


		{if $mostrar_F2_C2|default:false}
		<label for="F2_C2" {$class_F2_C2|default:""}>Tipo de Usuario</label>
		<select name="F2_C2" id="F2_C2" size="1" {$bloquear_F2_C2|default:""}>
                   {html_options options=$opciones_F2_C2|default:"" selected=$F2_C2|default:""}
                </select>
                <p class="expl">&nbsp;</p>
	        {else}
	        <input type="hidden" name="F2_C2" id="F2_C2" value="{$F2_C2|default:""}"/>
		{/if}

		{if $mostrar_F2_C3|default:false}
     	  	<label for="F2_C3" {$class_F2_C3|default:""}>Contrase&ntilde;a</label>
	     	<input type="password" name="F2_C3" id="F2_C3" value="{$F2_C3|default:""}" {$bloquear_F2_C3|default:""}/>
      	  	<label>&nbsp;</label>
     	        <p class="expl">(elija una contrase&ntilde;a - min. 6 caracteres, combine letras y n&uacute;meros)</p>

  		<label for="F2_C4" {$class_F2_C4|default:""}>Repita la Contrase&ntilde;a</label>
	     	<input type="password" name="F2_C4" id="F2_C4" value="{$F2_C4|default:""}" {$bloquear_F2_C4|default:""}/>
      	  	<label>&nbsp;</label>
     	        <p class="expl">(debe repetir el campo anterior para asegurar que la contrase&ntilde;a es correcta)</p>
 		{/if}

  		<label for="F2_C5" {$class_F2_C5|default:""}>Correo Electr&oacute;nico</label>
	     	<input type="text" name="F2_C5" id="F2_C5" value="{$F2_C5|default:""}" {$bloquear_F2_C5|default:""}/>
      	  	<label>&nbsp;</label>
     	        <p class="expl">(obligatorio - su direcci&oacute;n de correo electr&oacute;nico personal - 50 caracteres m&aacute;x.)</p>

  		<label for="F2_C6" {$class_F2_C6|default:""}>Nombre</label>
	     	<input type="text" name="F2_C6" id="F2_C6" value="{$F2_C6|default:""}" {$bloquear_F2_C6|default:""}/>
      	  	<label>&nbsp;</label>
     	        <p class="expl">(obligatorio - indique su nombre - 30 caracteres m&aacute;x.)</p>


  		<label for="F2_C7" {$class_F2_C7|default:""}>Primer Apellido</label>
	     	<input type="text" name="F2_C7" id="F2_C7" value="{$F2_C7|default:""}" {$bloquear_F2_C7|default:""}/>
      	  	<label>&nbsp;</label>
     	        <p class="expl">(obligatorio - indique su primer apellido - 30 caracteres m&aacute;x.)</p>


  		<label for="F2_C8" {$class_F2_C8|default:""}>Segundo Apellido</label>
	     	<input type="text" name="F2_C8" id="F2_C8" value="{$F2_C8|default:""}" {$bloquear_F2_C8|default:""}/>
      	  	<label>&nbsp;</label>
     	        <p class="expl">(opcional - indique su segundo apellido - 30 caracteres m&aacute;x.)</p>

  		<label for="F2_C9" {$class_F2_C9|default:""}>Edad</label>
	     	<input type="text" name="F2_C9" id="F2_C9" value="{$F2_C9|default:""}" {$bloquear_F2_C9|default:""}/>
      	  	<label>&nbsp;</label>
     	        <p class="expl">(obligatorio - se exije que tenga como m&iacute;nimo 18 a&ntilde;os para admitir su registro)</p>


		<label {$class_F2_C10|default:""}>Sexo</label>
		<div id="sexo">
		{if $bloquear_F2_C10|default:false}
		    {if $F2_C10 == "H"}
		     <label class="deshabilitada">Hombre</label>
                    {else}
      		     <label class="deshabilitada">Mujer</label>
                    {/if}
		{else}
	        {html_radios name="F2_C10" options=$opciones_F2_C10|default:"" selected=$F2_C10|default:""}
	        {/if}
                </div>
		{if $mostrar_F2_C11|default:false}
		<label for="F2_C11" {$class_F2_C11|default:""}>¿En qu&eacute; sector est&aacute; m&aacute;s interesado?</label>
		<select name="F2_C11" id="F2_C11" size="1" {$bloquear_F2_C11|default:""}>
                   {html_options options=$opciones_F2_C11|default:"" selected=$F2_C11|default:""}
                </select>
     	        <p class="expl">(obligatorio - elija el sector de la lista en el que est&eacute; m&aacute;s interesado)</p>
                {else}
       	           <input type="hidden" name="F2_C11" id="F2_C11" value="{$F2_C11|default:""}"/>
		{/if}

                <div class="clear"></div>
    	        <br></br>
     		{if $mostrar_Enviar|default:false}
		<input class="boton" type="submit" name="enviar" id="enviar" value="{$enviar|default:""}"/>
		{/if}
		{if $mostrar_Empresa|default:false}
		<input class="boton" type="submit" name="empresa" id="empresa" value="{$empresa|default:""}"/>
		{/if}
 	        {if $mostrar_Accesos|default:false}
		<input class="boton" type="submit" name="accesos" id="accesos" value="{$accesos|default:""}"/>
		{/if}
 	        {if $mostrar_Borrar|default:false}
		<input class="boton" type="submit" name="borrar" id="borrar" value="{$borrar|default:""}"/>
		{/if}
 	        {if $mostrar_Cancelar|default:false}
		<input class="boton" type="submit" name="cancelar" id="cancelar" value="{$cancelar|default:""}"/>
		{/if}
                <div class="clear"></div>
           </div>
        </form>
     </div>


