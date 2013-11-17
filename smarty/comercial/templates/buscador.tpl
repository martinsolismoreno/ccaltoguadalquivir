            <div id="contenedorBuscador">
            <div id="buscador">
                <form action="{$action}" method="post">
                <fieldset id="marco">
                    <div class="sector">
		    <fieldset>
                    <legend>{$leyenda|default:""}</legend>
		    <select name="sector" id="sector" size="1" {$bloquear_sector|default:""}>
                           {html_options options=$opciones_sector|default:"" selected=$sector|default:""}
                    </select>
                    </fieldset>
                    </div>
                    <div class="poblacion">
		    <fieldset>
                    <legend>... en ...</legend>
		    <select name="poblacion" id="poblacion" size="1" {$bloquear_poblacion|default:""}>
                            {html_options options=$opciones_poblacion|default:"" selected=$poblacion|default:""}
                    </select>
		    </fieldset>
                    </div>
                    <div class="botonBuscar">
		        <fieldset>
                        <legend>&nbsp;</legend>
                        <input type="submit" name="buscar" id="buscar" value="{$boton_buscar|default:"¡ busca !"}"/>
                        </fieldset>
                    </div>
                  </fieldset>
                </form>
            </div>
            <div class="clear"></div>
            </div>
