//apoyo con JQuery al men� CSS de Craig Erskine. (http://qrayg.com/experiment/cssmenus/) implementado para desarrollar una de las partes -->
//de mi men� principal. Seg�n el autor, es sobre todo para IE5 y IE6, ya que funciona en los navegadores modernos s�lo con CSS, que era mi intenci�n -->
    $(document).ready(function(){
    $("#navmenu-h li,#navmenu-v li").hover(
    function() { $(this).addClass("iehover"); },
    function() { $(this).removeClass("iehover"); }
    );});
