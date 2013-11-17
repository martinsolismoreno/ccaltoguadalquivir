    <div id="formCuenta">
       <form action="{$action}" method="post">
          <div class="formulario">
                         
  	  {if $nivel>={$smarty.const.GESTOR} AND $mostrarValidar|default:false}
	  <input class="botonValidar" type="submit" name="listEmpAvalidar" id="listEmpAvalidar" value="Empresas Pendientes de Validar" />
	  {/if}
          <div class="clear"></div>
	  {if $nivel>={$smarty.const.NORMAL}}
          <input class="boton" type="submit" name="formUsuario" id="formUsuario" value="Modificar tus datos" />
          {/if}
          <div class="clear"></div>
	  {if $nivel>={$smarty.const.NORMAL}}
  	  <input class="boton" type="submit" name="formEmpresa" id="formEmpresa" value="Modificar los datos de tu Empresa" />
  	  {/if}
          <div class="clear"></div>
	  {if $nivel>={$smarty.const.PREMIUM}}
	  <input class="boton" type="submit" name="formOferta"  id="formOferta" value="Subir una Oferta" />
  	  {/if}
          <div class="clear"></div>
	  {if $nivel>={$smarty.const.PREMIUM}}
	  <input class="boton" type="submit" name="listadoOfeEmp"  id="listadoOfeEmp" value="Ver tus Ofertas" />
  	  {/if}
          <div class="clear"></div>
	  {if $nivel>={$smarty.const.GESTOR}}
	  <input class="boton" type="submit" name="formNoticia" id="formNoticia" value="Subir una Noticia" />
  	  {/if}
          <div class="clear"></div>
	  {if $nivel>={$smarty.const.GESTOR}}
	  <input class="boton" type="submit" name="listadoNotUsu"  id="listadoNotUsu" value="Ver tus Noticias" />
  	  {/if}
  	  {if $nivel>={$smarty.const.GESTOR}}
          <div class="clear"><p class="linea"></p></div>
       	  {/if}
          <div class="clear"></div>
	  {if $nivel>={$smarty.const.GESTOR}}
	  <input class="boton" type="submit" name="listadoUsuarios" id="listadoUsuarios" value="Gestionar Usuarios" />
  	  {/if}
          <div class="clear"></div>
  	  {if $nivel>={$smarty.const.GESTOR}}
	  <input class="boton" type="submit" name="listadoEmpresas" id="listadoEmpresas" value="Gestionar Empresas" />
  	  {/if}
          <div class="clear"></div>
	  {if $nivel>={$smarty.const.GESTOR}}
	  <input class="boton" type="submit" name="listadoOfertas" id="listadoOfertas" value="Gestionar Ofertas" />
  	  {/if}
          <div class="clear"></div>
	  {if $nivel>={$smarty.const.GESTOR}}
	  <input class="boton" type="submit" name="listadoNoticias" id="listadoNoticias" value="Gestionar Noticias" />
  	  {/if}
          <div class="clear"></div>
  	  {if $nivel>={$smarty.const.ADMON}}
	  <input class="boton" type="submit" name="listadoAccesos" id="listadoAccesos" value="Ver Accesos" />
  	  {/if}
          </div>
       </form>
    </div>

