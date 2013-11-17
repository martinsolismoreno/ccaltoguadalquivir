{if $tipoNoticia=="completa"|default:"completa"}
     <div class="noticiaCompleta">
     <p class="titular">{$titular|default:""}</p>
{else}
     <div class="noticiaBreve">
     <a class="titular" href="{$link_titular|default:"#"}">{$titular|default:""}</a>
{/if}
     <p class="fecha">{$fecha|default:""}</p>
     <p class="resumen">{$resumen|default:""}</p>
     {if $tipoNoticia=="completa"|default:"completa"}
         <p class="texto">{$texto|default:""}</p>
         {if $link|default:""}
         <a class="link" href="{$link|default:""}" >M&aacute;s Informaci&oacute;n</a>
         {/if}
     {/if}
     <div class="clear"></div>
     </div>
