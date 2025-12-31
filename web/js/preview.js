$(document).ready(function() {
    var $container = $('#content-preview');
    var $content = $('#iframe-class');
    var $iframe = $('#iframe_web');
    var template = document.querySelector('#spinner-template');

    // Validaciones de existencia
    if ($container.length === 0 || $iframe.length === 0 || !template) {
        return;
    }

    // Clonar el contenido del template
    var $loaderContent = $(template.content.cloneNode(true)).children();

    $loaderContent.attr('id', 'iframe-loader');

    // Transformar el spinner (Hacerlo grande y del color correcto)
    $loaderContent.find('.spinner-border')
        .removeClass('spinner-border-sm') // Quitamos la clase "sm" para que sea tamaño normal
        .addClass('color-main-1') // Añadimos tu color
        .css({ // Forzamos el tamaño de 80px
            'width': '80px',
            'height': '80px',
            'border-width': '6px'
        });

    // Inyectar en el DOM
    $container.prepend($loaderContent);

    // Lógica de Reescalado Multidispositivo
    const MARGIN_BUFFER = 150;

    function resizeDevice() {
        if ($content.hasClass('device-desktop')) {
            return;
        }

        // Obtener espacio disponible en el contenedor padre
        var availableWidth = $container.width();
        var availableHeight = $container.height();

        var currentWidth = $("#iframe_web").outerWidth() + hgt_navbar;
        var currentHeight = $("#iframe_web").outerHeight() + hgt_navbar;

        // Calcular escala necesaria
        var scaleX = (availableWidth - MARGIN_BUFFER) / currentWidth;
        var scaleY = (availableHeight - MARGIN_BUFFER) / currentHeight;

        // Usamos el menor de los dos para asegurar que no se salga por ningún lado
        var scale = Math.min(scaleX, scaleY);

        // Aplicar la variable CSS
        $iframe.css('--scale-factor', scale);
    }

    // Ejecutar al inicio y al redimensionar ventana
    resizeDevice();

    $(window).on('resize', function() {
        resizeDevice();
    });

    // Función para ocultar y eliminar
    function hideLoader() {
        $('#iframe-loader').fadeOut(400, function() {
            $(this).remove();
        });
    }

    // Eventos de carga
    $iframe.on('load', function() {
        setTimeout(function() {
            hideLoader();
        }, 500);
    });
});