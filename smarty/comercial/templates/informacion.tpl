<div id="texto" class="informacion">

   <p><span class="iniparrafo">S</span><span class="iniparrafo"></span>iguiendo los contenidos del curso el portal est&aacute; desarrollado haciendo uso de XHTML, CSS, JavaScript y PHP, con soporte en una base de datos MySQL y tambi&eacute;n como requisito era hacer uso de tecnolog&iacute;a AJAX, notaci&oacute;n JSON o XML y trabajar con la funcionalidad DOM. Otras decisiones han sido m&iacute;as, como programar PHP Orientado a Objetos, evitar el uso de frameworks para un conocimiento de base de todas las tecnolog&iacute;as, y, por mi experiencia tanto en an&aacute;lisis como programaci&oacute;n seguir un patr&oacute;n MVC. Tambi&eacute;n decid&iacute; no hacer uso de librer&iacute;as, aunque al final, para poder enviar correos y evitar los problemas dependientes del servidor con las funciones de mail de PHP, he incorporado a posteriori la librer&iacute;a PHPmailer para este cometido.</p>

   <p><span class="iniparrafo">P</span>or otro lado, tambi&eacute;n fue elecci&oacute;n m&iacute;a hacer uso de plantillas Smarty en donde se recoge todo el XHTML y que me eran imprescindibles para una completa separaci&oacute;n de c&oacute;digo y presentaci&oacute;n y para poder seguir el modelo MVC. Tal es la separaci&oacute;n que hasta cualquier etiqueta o mensaje que vea en el portal se recoge en &uacute;nico archivo. La modularizaci&oacute;n y estructuraci&oacute;n ha producido un c&oacute;digo muy f&aacute;cilmente actualizable y mantenible. Por ejemplo terminar de desarrollar la parte de traducirlo al ingl&eacute;s no ser&iacute;a complicado pues consistir&iacute;a en llevar los textos del archivo mencionado a una base de datos y sustituir sus variables por la llamada a una funci&oacute;n que accediese a ella indicando el idioma que requiere el usuario.</p>

   <p><span class="iniparrafo">Y</span>a he tenido la oportunidad de comprobar la "bondad" del c&oacute;digo al trasladarlo al servidor gratuito donde lo est&aacute; viendo instalado y tambi&eacute;n cuando me vi en la necesidad de hacer peque&ntilde;os retoques necesarios para que se viese completamente bien su presentaci&oacute;n en m&uacute;ltiples navegadores. El sitio ha sido probado con &eacute;xito en Firefox (tanto para Windows como Linux, este &uacute;ltimo sirvi&oacute; de base de pruebas para el desarrollo del portal) Chrome (Windows) y su versi&oacute;n Chromium (Linux), Opera (Windows), Safari (Windows) y por supuesto el problem&aacute;tico Microsoft Internet Explorer en sus &uacute;ltimas versiones. Hasta IE6 s&oacute;lo me descuadra el men&uacute; flotante "Tu Comarca", pero si Microsoft ha celebrado ya <a href="http://www.bbc.co.uk/mundo/noticias/2012/01/120104_tecnologia_microsoft_explorer_entierro_lav.shtml" >la "muerte" de ese navegador</a>, tom&eacute; la determinaci&oacute;n de no insistir en su correcci&oacute;n ya que implicaba problemas con otras versiones y plataformas. Por otro lado al estar el c&oacute;digo validado XHTML y CSS, en principio cualquier navegador "debe" entenderlo si se ajusta a los est&aacute;ndares (ver nota final).</p>

   <p><span class="iniparrafo">A</span>unque ni de lejos estaba pensado orientar el desarrollo a dispositivos m&oacute;viles, he comprobado que gracias a la buena maquetaci&oacute;n y el uso de est&aacute;ndares, como efecto "colateral", su funcionamiento, presentaci&oacute;n y operatividad en tabletas de 10" como iPad, con una resoluci&oacute;n de 1024x768 (para la que est&aacute; optimizado) es perfecta. As&iacute;, he probado su uso en Safari, Chrome y Opera para iPad, as&iacute; como el navegador Mercury tambi&eacute;n para iPad con distintos de los "motores" que permite, entro ellos el de Firefox y en todos es completamente funcional; muy importante en los tiempos que corren. Con m&aacute;s dificultad por el tama&ntilde;o, pero es hasta operativo en el navegador por defecto de un tel&eacute;fono m&oacute;vil Symbian... Estimo que ser&iacute;a f&aacute;cil pasarlo a una versi&oacute;n m&oacute;vil, pues como he comentado, la presentaci&oacute;n es totalmente independiente del grueso de la programaci&oacute;n.</p>

    <p><span class="iniparrafo">A</span>simismo consider&eacute; un reto ajeno a los requisitos y es que el portal fuese totalmente operativo sin JavaScript y sin cookies, por lo que para mantener la sesi&oacute;n he llevado yo todo el control de su identificaci&oacute;n mediante URL en el sitio. La soluci&oacute;n puede crear una posible vulnerabilidad al mostrar el identificador de sesi&oacute;n en la URL, pero por su implementaci&oacute;n es instant&aacute;neo desactivar esa funci&oacute;n en cualquier momento. En ese caso, el sitio queda dependiente a que se permita el uso de cookies.</p>

    <p><span class="iniparrafo">O</span>tros temas importantes de seguridad tambi&eacute;n los he tenido en cuenta como por ejemplo evitar ataques por inyecci&oacute;n SQL. Un uso intensivo de expresiones regulares sirve para validar los formularios e internamente cualquier dato susceptible de ser admitido por los objetos de las clases encargadas de interrelacionarse con la BD. Adem&aacute;s de seguridad, las expresiones regulares me han dado la comodidad y facilidad de controlar cualquier campo y el poder actualizar sus requisitos de validaciones de forma muy f&aacute;cil y concentrada; pues igualmente todas estas tareas de validaci&oacute;n est&aacute;n separadas del c&oacute;digo y reunidas en un &uacute;nico repositorio.</p>

    <p><span class="iniparrafo">T</span>ambi&eacute;n he conseguido que haya cierto dinamismo a&uacute;n en caso que no se permita JavaScript. Para ello, las noticias y ofertas cambian cuando el usuario refresca la p&aacute;gina o sale y entra de aquellas en las que se muestran. Pruebe a desactivar JavaScript y c&oacute;mo el portal lo detecta y se adapta a la situaci&oacute;n.</p>

    <p><span class="iniparrafo">Y</span> para terminar quisiera mostrar la paleta de colores usada en todo el portal, y para la que tambi&eacute;n he seguido un patr&oacute;n uniforme. La elecci&oacute;n de estos colores no ha sido aleatoria y atienden a unos "pantone" concretos: est&aacute;n inspirados en los que se han usado tradicionalmente en la cer&aacute;mica andalus&iacute;, actividad muy relacionada con la zona para la que est&aacute; dirigida el portal. As&iacute;, estos colores son una aproximaci&oacute;n RGB a los usados en esos objetos: azul cobalto (#294390), verde manganeso (#33564B), marr&oacute;n (#36260C), amarillo "luz dorada" (#D6BC27) y carmes&iacute; (#800033). El mandala que se usa como logotipo del sitio ha sido pintado por m&iacute; recogiendo y combinando los colores de la citada paleta.</p>

<p><img class="paleta" src="{$smarty.const.PALETA}" alt="paleta colores" title="paleta colores" /></p>

    <p><span class="iniparrafo">N</span>o me quiero extender m&aacute;s. S&oacute;lo espero que est&eacute; probando el sitio en un buen momento, puesto que se nota mucho el rendimiento del servidor, donde a veces puede que incluso le aparezca como no disponible, sobre todo el acceso a MySQL, muy restringido. Realmente el c&oacute;digo es muy liviano y est&aacute; muy optimizado su rendimiento, ya que se ejecuta perfectamente hasta en un servidor Apache con MySQL "movible" en pendrive, donde lo suelo llevar instalado. Pero lo usual en estos servidores gratuitos es que sean muy limitados, de hecho no me ha sido f&aacute;cil poder encontrar uno donde adaptarlo; entre otros factores por el uso de plantillas Smarty pues es necesario que el servidor las tenga o permita su instalaci&oacute;n. Por otro lado, las complicaciones encontradas en esta tarea han sido un buen reto y el resultado es poder verlo funcionar fuera de mi servidor local.</p>

    <p><span class="iniparrafo">S</span>inceramente, s&oacute;lo me queda agradecer mucho la visita y si ha llegado leyendo hasta aqu&iacute;, m&aacute;s a&uacute;n por su tiempo y el inter&eacute;s prestado.</p>

    <p>Mart&iacute;n.</p>


<p class="nota"><span>NOTA:</span> Todo el portal est&aacute; validado XHTML y CSS, aunque debido a que lo est&aacute; viendo desde un servidor gratuito, &eacute;ste introduce c&oacute;digo "basura" que origina que aparezcan 3 o 4 errores en XHTML en todas las p&aacute;ginas. Que elimine el c&oacute;digo mediante JavaScript no impide que se refleje en el c&oacute;digo fuente que usa el validador.</p> 

</div>