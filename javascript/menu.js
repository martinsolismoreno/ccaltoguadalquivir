//apoyo con JQuery al menú CSS de Craig Erskine. (http://qrayg.com/experiment/cssmenus/) implementado para desarrollar una de las partes -->
//de mi menú principal. Según el autor, es sobre todo para IE5 y IE6, ya que funciona en los navegadores modernos sólo con CSS, que era mi intención -->
    $(document).ready(function(){
    $("#navmenu-h li,#navmenu-v li").hover(
    function() { $(this).addClass("iehover"); },
    function() { $(this).removeClass("iehover"); }
    );});
