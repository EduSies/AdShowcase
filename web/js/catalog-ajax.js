
const width_transition_search = 200;

var currentOffset = 12;
var isLoading = false;
var allLoaded = false;
var searchTimeout;

var hgt_filter = $("#filter-content").outerHeight();
var hgt_txt_filter = $("#search-filter").outerHeight(true);
var hgt_selector_filter = $("#search-filter").outerHeight();
var hgt_txt_banner = $(".text-banner-adgallery").parent().height();

// Configuración de selectores
const selectors = {
    containerCards: '#cards-container',
    loader: '#loader',
    searchContainer: '#adshowcase-header-search',
    btnSearch: '#btnSearch',
    btnSearchCancel: '#btnSearchCancel',
    inputs: {
        products: '#filter_products',
        formats: '#filter_formats',
        devices: '#filter_devices',
        countries: '#filter_countries',
        search: '#filter-search'
    },
    containerMenuRight: '#menu-right'
};

// --- 1. GESTIÓN DE URL ---
function updateURLWithFilters() {
    if (!history.pushState) return; // Navegadores antiguos

    let params = new URLSearchParams(window.location.search);

    // Helper para actualizar o borrar parámetros
    const updateParam = (p, selector, paramName) => {
        let val = $(selector).val();

        if (Array.isArray(val) && val.length === 0) {
            val = null;
        }

        // Solo añadimos si existe, no es null y no es cadena vacía
        if (val && val !== "" && val !== null) {
            p.set(paramName, val);
        } else {
            // Si está vacío, LO BORRAMOS de la URL
            p.delete(paramName);
        }
    };

    updateParam(params, selectors.inputs.products, 'products');
    updateParam(params, selectors.inputs.formats, 'formats');
    updateParam(params, selectors.inputs.devices, 'devices');
    updateParam(params, selectors.inputs.countries, 'countries');
    updateParam(params, selectors.inputs.search, 'search');

    let queryString = params.toString();

    let newUrl = window.location.pathname + (queryString ? '?' + queryString : '');

    window.history.replaceState(null, '', newUrl);
}

// --- 2. FUNCIÓN PRINCIPAL DE FILTRADO ---
function triggerFilter(reset = true) {
    if (reset) {
        currentOffset = 0;
        allLoaded = false;
        $(selectors.containerCards).css('opacity', '0.5');
    }

    if (isLoading || (allLoaded && !reset)) {
        return;
    }

    isLoading = true;

    if (!reset){
        $(selectors.loader).removeClass('d-none');
    }

    // Actualizamos la URL antes de enviar (o después, según prefieras)
    updateURLWithFilters();

    // Recoger valores
    var data = {
        products: $(selectors.inputs.products).val(),
        formats: $(selectors.inputs.formats).val(),
        devices: $(selectors.inputs.devices).val(),
        countries: $(selectors.inputs.countries).val(),
        search: $(selectors.inputs.search).val(),
        limit: currentOffset
    };

    $.ajax({
        url: ajaxUrl, // Asegúrate de que esta variable está definida en la vista (main-catalog.php o index)
        type: 'POST',
        data: data,
        success: function(response) {
            if (reset) {
                $(selectors.containerCards).html(response.creatives);
                $(selectors.containerCards).css('opacity', '1');

                if (response.count === 0) allLoaded = true;
            } else {
                $(selectors.containerCards).append(response.creatives);

                if (response.count === 0) {
                    allLoaded = true;
                } else {
                    if (response.count < 12) allLoaded = true;
                }
            }

            currentOffset += 12; // Aumentar offset según tu pageSize
            isLoading = false;
            $(selectors.loader).addClass('d-none');
        },
        error: function() {
            isLoading = false;
            $(selectors.containerCards).css('opacity', '1');
            $(selectors.loader).addClass('d-none');
        }
    });
}

function setPositionSearchBarDesktop(isAnimating) {
    // 1. Buscamos el filtro de referencia para alinearnos (usamos 'formats')
    let $refElement = $(selectors.inputs.formats).first();

    // 2. Calculamos posición
    let position = $refElement.offset(); // Usamos offset para posición absoluta en pantalla

    // 3. Calculamos el espacio reservado a la derecha
    let rightReservedSpace = $(selectors.btnSearch).outerWidth(true) + $(selectors.containerMenuRight).outerWidth(true);

    let availableWidth = ($(window).width()) - (position.left + rightReservedSpace);

    // 4. Limpiamos estilos anteriores
    $(selectors.searchContainer).removeAttr('style');

    // 5. Calculamos el desplazamiento de la animación
    let offsetAnimacion = (isAnimating) ? width_transition_search : 0;

    // 6. Aplicamos el CSS
    $(selectors.searchContainer).css({
        'position': 'absolute',
        'left': (position.left + offsetAnimacion) + 'px',
        'width': availableWidth + 'px',
        'z-index': 1050
    });
}

// --- 3. ANIMACIONES DE LA BARRA DE BÚSQUEDA ---

// Abrir Búsqueda
$(document).on('click', selectors.btnSearch, function (e) {
    e.preventDefault();

    setPositionSearchBarDesktop(true);

    $(this).stop().animate({
        left: '+=25',
        opacity: '-=1',
    }, 300, function () {
        // Manipulación de clases Bootstrap para mostrar el input
        $(selectors.btnSearchCancel).removeClass('d-lg-flex');
        $(selectors.searchContainer).removeClass('d-lg-none');

        // Animación del contenedor del input
        $(selectors.searchContainer).stop().animate({
            left: '-=' + width_transition_search,
            opacity: '+=1',
        }, 300, function () {
            $(selectors.btnSearchCancel).addClass('d-lg-flex');
        });

        $(selectors.inputs.search).focus();
    });
});

// Cerrar Búsqueda (Cancelar)
$(document).on('click', selectors.btnSearchCancel, function (e) {
    e.preventDefault();

    // Ocultar botón cancelar
    $(this).addClass('d-none').removeClass('d-lg-flex');

    // Animar cierre del contenedor
    $(selectors.searchContainer).stop().animate({
        left: '+=' + width_transition_search,
        opacity: '-=1',
    }, 300, function () {
        $(selectors.searchContainer).addClass('d-lg-none');

        // Restaurar botón de lupa
        $(selectors.btnSearch).css({'visibility': 'visible'});
        $(selectors.btnSearch).stop().animate({
            left: '-=25',
            opacity: '+=1',
        }, 300, function () {
            $(selectors.inputs.search).val('');
            triggerFilter(true);
        });
    });
});

// --- EVENTOS ---
// Evento de Búsqueda (Input Keyup) con debounce
$(selectors.inputs.search).on('keyup', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(function() {
        triggerFilter(true);
    }, 500);
});

// Detectar cambios en los Dropdowns (Filtros)
// Agrega aquí los selectores de tus dropdowns si quieres que filtren al cambiar
$(selectors.inputs.products + ', ' + selectors.inputs.formats + ', ' + selectors.inputs.devices + ', ' + selectors.inputs.countries).on('change', function() {
    triggerFilter(true);
});

// Scroll Infinito
$(window).on('scroll', function() {
/*    console.log($(window).scrollTop(),$(window).height());
    if($(window).scrollTop() + $(window).height() > $(document).height() - 200) {
        if(!isLoading && !allLoaded) {
            triggerFilter(false); // false = no resetear, añadir al final
        }
    }
*/

    hgt_filter = $("#filter-content").outerHeight();
    hgt_txt_banner = $(".text-banner-adshowcase").parent().height();

    if (window.scrollY >= ((hgt_filter - hgt_txt_banner) + hgt_navbar)) {

        if (!$("#search-filter").is(':hidden')) {
            $("#filter-content").css('top', -((hgt_filter - hgt_txt_banner) + hgt_navbar));
            $("#filter-content").css('position', 'fixed');
            $("#filter-content").css('width', '100%');
            $(".adshowcase-bg-filter").css('opacity', 0);

            hgt_selector_filter = $("#search-filter").outerHeight();
            $("#search-filter-tags").css('top', ((hgt_filter - hgt_txt_filter) - hgt_selector_filter));
            $("#search-filter-tags").css('position', 'fixed');

            let hgt_search_filter_tags = ($("#search-filter-tags").outerHeight() > 0) ? $("#search-filter-tags").outerHeight() : 0;
            $("#control-scroll-filter").css('top', (hgt_navbar + hgt_filter + hgt_search_filter_tags));
        }

    } else {

        $("#filter-content").removeAttr('style');
        $("#search-filter-tags").removeAttr('style');
        $("#control-scroll-filter").removeAttr('style');

        if (device == 1 && ((window.innerHeight + ((hgt_filter - hgt_txt_banner) + hgt_navbar)) < document.body.scrollHeight)) {
            $("body").attr('style', 'height: ' + (window.innerHeight + ((hgt_filter - hgt_txt_banner) + hgt_navbar)) + 'px !important');
        } else {
            $("body").removeAttr('style');
        }

        $(".adshowcase-bg-filter").css('opacity', (1 - (window.scrollY / 100)));

    }

    let pos = window.innerHeight + window.scrollY;

    console.log("POS: "+Math.ceil(pos)+" POS: "+pos+" window.innerHeight: "+window.innerHeight+" window.scrollY: "+window.scrollY+" document.body.scrollHeight: "+document.body.scrollHeight);

    if (Math.ceil(pos) >= document.body.scrollHeight) {
        console.log('entra');
        triggerFilter(false);
    }
});

// Inicialización: Leer URL al cargar la página si hay filtros
$(document).ready(function() {
    let params = new URLSearchParams(window.location.search);

    if (params.toString()) {
        // Rellenar inputs con valores de URL
        if(params.has('search')) $(selectors.inputs.search).val(params.get('search'));
        if(params.has('products')) $(selectors.inputs.products).val(params.get('products'));
        if(params.has('formats')) $(selectors.inputs.formats).val(params.get('formats'));
        if(params.has('devices')) $(selectors.inputs.devices).val(params.get('devices'));
        if(params.has('countries')) $(selectors.inputs.countries).val(params.get('countries'));

        // 2. Si hay búsqueda activa, mostrar la barra visualmente
        if(params.has('search') && params.get('search') !== '') {
            $(selectors.searchContainer).removeClass('d-lg-none');
            $(selectors.btnSearch).css({opacity: 0, visibility: 'hidden'});
            $(selectors.btnSearchCancel).removeClass('d-none').addClass('d-flex');
        }

        // 3. Ejecutar el filtro inicial (opcional, si quieres que cargue filtrado al abrir el link)
        //triggerFilter(true);
    }
});