    <div id={$nombreTabla|default:""}>
       <table>
          {section name=columnas start=0 loop={$numColumnas|default:0} step=1}
             <th class="c{$smarty.section.columnas.index}">
                 <a href="{$link_th{$smarty.section.columnas.index}|default:""}" {$class_link_th{$smarty.section.columnas.index}|default:""}>
                 {$dato_th{$smarty.section.columnas.index}|default:""}
                 </a>
             </th>
          {/section}
          {section name=filas start=0 loop={$numFilas|default:0} step=1}
             {if ($smarty.section.filas.index%2)==1}
                <tr class="impar">
             {else}
	        <tr>
	     {/if}
             {section name=columnas start=0 loop={$numColumnas|default:0} step=1}
 	        <td class="c{$smarty.section.columnas.index}">
                    {$link_td{$smarty.section.filas.index}{$smarty.section.columnas.index}|default:""}
                    {$dato_td{$smarty.section.filas.index}{$smarty.section.columnas.index}|default:""}
                    {if $link_td{$smarty.section.filas.index}{$smarty.section.columnas.index}|default:false}</a>{/if}
                </td>
             {/section}
                </tr>
          {/section}
       </table>
       <div id="pieNav">
          <div id="navIzqda">
          {if $pagAct > 1}<a href="{$link_ini|default:""}">&#60&#60&nbsp;&nbsp;&nbsp;</a>{/if}
          {if $pagAct > 1}<a href="{$link_ant|default:""}">&nbsp;&nbsp;&nbsp;&#60&nbsp;&nbsp;</a>{/if}
          <p></p>
          </div>
          <div id="navCentral">
          <p>P&aacute;gina {$pagAct} de {$totPag}</p>
          </div>
          <div id="navDcha">
          {if $pagAct < $totPag}<a href="{$link_sgt|default:""}">&nbsp;&nbsp;&#62&nbsp;&nbsp;</a>{/if}
          {if $pagAct < $totPag}<a href="{$link_fin|default:""}">&nbsp;&nbsp;&#62&#62&nbsp;&nbsp;</a>{/if}
          </div>
       </div>
       {if $totPag>1}
       <div class="clear"></div>
       <div id="formIrPag">
          <form action={$action} method="post">
             <div class="formulario">
                <label for="pagina">Ir a p&aacute;gina</label>
	        <input type="text" name="pagina" id="pagina" value=""/>
	        <input class="boton" type="submit" name="enviar" id="enviar" value="->" />
             </div>
          </form>
          <div class="clear"></div>
       </div>
       {/if}
       {if $mostrar_Limpiar|default:false}
       <form action={$action} method="post">
       <div id="formLimpiarHistoria" class="clear">
  	    <input class="boton" type="submit" name="limpiar" id="limpiar" value="Limpiar Historial" />
       </div>
       </form>
       {/if}
   </div>


