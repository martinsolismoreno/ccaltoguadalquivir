<div class="vistaEmpresa">

     <div class="logoempresa"><img src="{$imagen}" alt="Logotipo de la Empresa"/></div>

     <div class="textoEmpresa">
     <div class="nombre"><p>{$empresa|default:""}</p></div>
     <div class="descripcion"><p>{$descripcion|default:""}</p></div>
     <div class="direccion"><img src="{$smarty.const.IMG_DIRECCION}" alt="Icono Direcci&oacute;n"/><p>&nbsp;{$direccion|default:""} ({if {$pedania|default:false}}{$pedania|default:""} - {/if}{$poblacion|default:""})</p></div>
     <div class="horario"><img src="{$smarty.const.IMG_HORARIO}" alt="Icono Reloj"/><p>&nbsp;{$horario|default:""}</p></div>
     <div class="clear"></div>
     </div>

     <div class="franja"><p>&nbsp;</p></div>
     <div class="datosContacto">
     {if !$email|default:false}<div class="vacio"><p>&nbsp;</p></div>{/if}
     {if !$web|default:false}<div class="vacio"><p>&nbsp;</p></div>{/if}
     {if !$telefono2|default:false}
          <div class="vacio"><p>&nbsp;</p></div>
          <div class="telefono1"><p><strong>{$telefono1|default:""}</strong></p></div>
     {else}
          <div class="telefono1"><p><strong>{$telefono1|default:""}</strong></p></div>
          <div class="telefono2"><p><strong>{$telefono2|default:""}</strong></p></div>
     {/if}
     <div class="fax"><p>{if $fax}Fax:{/if}&nbsp;{$fax|default:""}</p></div>
     {if $email}<div class="email"><p><a href="mailto:{$email|default:""}">Correo Electr&oacute;nico</a></p></div>{/if}
     {if $web}<div class="web"><p><a href="{$web}" >Web de la Empresa</a></p></div>{/if}
     <div class="ofertas"><p><a href="{$ofertas|default:"#"}">Ver sus Ofertas</a></p></div>
     </div>

    <div class="clear"></div>
</div>

<div class="formVistas">
     <form action="{$action|default:""}" method="post" enctype="multipart/form-data" >
        <div class="formulario">
          {if $mostrar_Enviar|default:false}
             <input class="boton" type="submit" name="enviar" id="enviar" value="{$enviar|default:""}"/>
         {/if}
         {if $mostrar_Validar|default:false}
             <input class="boton" type="submit" name="validar" id="validar" value="{$validar|default:""}"/>
        {/if}
        </div>
     </form>
</div>     

