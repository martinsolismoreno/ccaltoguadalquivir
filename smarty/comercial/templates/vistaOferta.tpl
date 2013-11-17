     <div class="oferta">
     <div class="izqda">
          <p class="tituloOferta">{$oferta|default:""}</p>
         {if $fecha|default:""}<p class="fecha">V&aacute;lida hasta el {$fecha|default:""}</p>{/if}
          <p class="condiciones">{$condiciones|default:""}</p>
          <a class="link" href="{$link_empresa|default:"#"}">{$empresa|default:"Ver Empresa"}</a>          
     </div>
     <div class="dcha">
     <p class="texto">{$texto|default:""}</p>
     </div>
     <div class="clear"></div>
     </div>