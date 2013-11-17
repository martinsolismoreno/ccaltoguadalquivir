<div id="noticias">
   <div id="cabeceraNoticias"><p>NOTICIAS</p></div>
   <div class="clear"></div>
   {if !$javascript}
   <div class="noticia">
        <p class="atencionjs">ATENCION: No tiene Javascript Activado</p>
        <p class="resumenjs">Aunque la web es perfectamente operativa, se pierden algunas ventajas y comodidad en la navegación, por lo que se recomienda su activaci&oacute;n.</p>
        <a class="linkjs" href="{$comprobarjs|default:"#"}">&gt; Comprobar si se ha activado la ejecuci&oacute;n de scripts &lt;</a>
   </div>
   {/if}
   <div class="clear"></div>
   <div id="listaNoticias">
   {if !$javascript}
        <div class="noticia">
             <h4><a href="{$link_titular|default:"#"}">{$titular|default:""}</a></h4>
             <p class="info"><span class="fecha">{$fecha|default:""}</span></p>
             <p class="resumen">{$resumen|default:""}</p>
             {if $texto!=""|default:""}
             <br/><br/>
             <p class="texto">{$texto|default:""}</p>
             {if $link!=""|default:""}
                 <a class="link" href="{$link|default:"#"}">
                 {if $link_titular=="#"}
                 Enviar Noticia
                 {else}
                 M&aacute;s Informaci&oacute;n</a>
                 {/if}
             {/if}
             {/if}
        </div>
   {/if}
   </div>
   <div class="clear"></div>
</div>
