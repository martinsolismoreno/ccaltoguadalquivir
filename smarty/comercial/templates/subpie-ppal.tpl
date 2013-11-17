   <div id="subpie-ppal">
       {if $mostrar_texto|default:true}
       <div id="navIzqda">
       {if $pagAct > 1}<a href="{$link_ini|default:""}">&nbsp;Inicio&nbsp;&nbsp;</a>
                       <p>|</p>
                       <a href="{$link_ant|default:""}">&nbsp;&nbsp;Anterior</a>
       {/if}
       <p>&nbsp;</p>
       </div>
       <div id="navPag1">
          <form action="{$action}" method="post">
                <p>p&aacute;gina</p><input type="text" name="pagina" id="pagina" value="{$pagAct}"/><p>de {$totPag}</p>
          </form>
       </div>
       <div id="navPag2">
          <form action="{$action|default:""}" method="post">
                <p>| m&aacute;x </p><input type="text" name="numreg" id="numreg" value="{$tamPag}"/><p> registro{if $tamPag>1}s{/if} por p&aacute;gina</p>
          </form>
       </div>
       <div id="navDcha">
       <p>&nbsp;</p>
       {if $pagAct < $totPag}<a href="{$link_sgt|default:""}">Siguiente&nbsp;&nbsp;</a>
                             <p>|</p>
                             <a href="{$link_fin|default:""}">&nbsp;&nbsp;Final&nbsp;</a>
       {/if}
       </div>
       {/if}       
       <div class="clear"></div>
   </div>