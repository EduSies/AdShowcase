// ==========================================
// CONFIGURACIÓN GLOBAL
// ==========================================
// Constantes que definen el comportamiento de la UI y la lógica de negocio.
const CONFIG = {
    transitionSearchWidth: 200, // (px) Desplazamiento de la animación al abrir la barra de búsqueda
    headerGap: 8, // (px) Espacio de ajuste para el cálculo del 'sticky' header
    itemsPerLoad: 12, // Paginación: Cantidad de tarjetas a cargar por petición AJAX
    searchDebounceTime: 500, // (ms) Tiempo de espera tras dejar de escribir para lanzar la búsqueda
    filterKeys: ['products', 'formats', 'devices', 'countries'] // IDs lógicos de los dropdowns de filtro
};

// ==========================================
// MAPA DE SELECTORES DOM
// ==========================================
// Centraliza todas las referencias al DOM. Si cambia el HTML, solo editas aquí.
const selectors = {
    // --- Contenedores Generales ---
    containerCards: '#cards-container .list-cards', // Donde se inyectan las creatividades
    loader: '#loader', // Elemento de carga (spinner)
    body: 'body',

    // --- Layout y Efectos de Scroll ---
    // Elementos usados para calcular cuándo fijar la barra de filtros (sticky)
    layout: {
        filterContent: '#filter-content', // Contenedor principal de filtros
        searchFilter: '#search-filter', // Fila de los dropdowns
        textBanner: '.text-banner-adshowcase', // Texto del banner (para calcular altura)
        bgFilter: '.adshowcase-bg-filter', // Fondo con imagen/color del filtro
        scrollControl: '#control-scroll-filter', // Contenedor que ajusta su margen al hacer sticky
        backToTop: '.circle-icon' // Botón para volver al top de la página
    },

    // --- Barra de Búsqueda (Header) ---
    search: {
        container: '#adshowcase-header-search', // Contenedor que se anima
        btnOpen: '#btnSearch', // Botón Lupa (Abrir)
        btnCancel: '#btnSearchCancel', // Botón X (Cerrar/Cancelar)
        containerMenuRight: '#menu-right' // Menú derecho (usado para calcular espacio disponible)
    },

    // --- Inputs de Formulario ---
    inputs: {
        products: '#filter-products', // Select de Industria/Producto
        formats: '#filter-formats', // Select de Formatos
        devices: '#filter-devices', // Select de Dispositivos
        countries: '#filter-countries', // Select de Países
        search: '#filter-search' // Input de texto libre
    },

    // --- Etiquetas de Filtros Activos (Pills) ---
    tags: {
        container: '#search-filter-tags', // Barra azul donde aparecen los tags
        preview: '#search-filter-preview', // Contenedor interno de los tags
        btnClose: '.btn-close-pill', // (Clase dinámica) La 'X' de cada tag
        btnDeleteAll: '.btn-delete-all-filters' // Botón "Borrar todos"
    },

    // --- Templates HTML (<template>) ---
    // Referencias a los scripts tipo text/template en el HTML para evitar HTML en JS
    templates: {
        skeleton: '#skeleton-template', // Estructura de carga (esqueleto)
        pill: '#filter-pill-template', // Estructura de una etiqueta individual
        deleteAll: '#filter-delete-all-template' // Estructura del contenedor de etiquetas + botón borrar
    }
};

// ==========================================
// ESTADO DE LA APLICACIÓN
// ==========================================
// Variables mutables que controlan el flujo de datos y la interfaz en tiempo real.
let state = {
    currentOffset: CONFIG.itemsPerLoad, // Puntero para la paginación (Offset SQL)
    isLoading: false, // Semáforo para evitar peticiones AJAX simultáneas
    allLoaded: false, // Bandera: true si el servidor devolvió 0 resultados (fin del scroll)
    searchTimeout: null, // ID del timer para el debounce del buscador

    // Guardamos alturas para no recalcularlas en cada evento 'scroll'
    hgtFilter: 0, // Altura del bloque de filtros
    hgtTxtBanner: 0, // Altura del texto del banner
    hgtNavbar: 0 // Altura del navbar
};

// ==========================================
// HELPERS Y UTILIDADES
// ==========================================

/**
 * Actualiza la URL del navegador sin recargar la página
 */
function updateURLWithFilters() {
    if (!history.pushState) return;

    let params = new URLSearchParams(window.location.search);

    const updateParam = (p, selector, paramName) => {
        let $input = $(selector);
        let val = $input.val();

        let slug = $input.find('option:selected').data('slug');
        let valueForUrl = (slug !== undefined && slug !== null && slug !== "") ? slug : val;

        if (Array.isArray(valueForUrl) && valueForUrl.length === 0) valueForUrl = null;

        if (valueForUrl && valueForUrl !== "" && valueForUrl !== null) {
            p.set(paramName, valueForUrl);
        } else {
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

/**
 * Muestra esqueletos de carga
 * @param {number} count Número de esqueletos
 * @param {boolean} append Si es true añade al final, false limpia el contenedor
 */
function showSkeletons(count, append = false) {
    const template = document.querySelector(selectors.templates.skeleton);
    const $container = $(selectors.containerCards);

    if (!template) return;

    if (!append) {
        $container.html('');
        $container.css('opacity', '1');
    }

    let skeletonsFragment = document.createDocumentFragment();

    for (let i = 0; i < count; i++) {
        // Clonamos el contenido del template
        let clone = template.content.cloneNode(true);
        skeletonsFragment.appendChild(clone);
    }

    $container.append(skeletonsFragment);
}

// ==========================================
// LÓGICA DE UI (BÚSQUEDA Y SCROLL)
// ==========================================

function setPositionSearchBarDesktop(isAnimating) {
    let $refElement = $(selectors.inputs.formats).first();
    let position = $refElement.offset();

    let rightReservedSpace = ($(selectors.search.btnOpen).outerWidth(true) * 3) +
        $(selectors.search.containerMenuRight).outerWidth(true);

    let availableWidth = ($(window).width()) - (position.left + rightReservedSpace);

    $(selectors.search.container).removeAttr('style');

    let offsetAnimacion = (isAnimating) ? CONFIG.transitionSearchWidth : 0;

    $(selectors.search.container).css({
        'position': 'absolute',
        'left': (position.left + offsetAnimacion) + 'px',
        'width': availableWidth + 'px',
        'z-index': 1040
    });
}

function handleStickyFilterScroll() {
    state.hgtFilter = $(selectors.layout.filterContent).outerHeight();
    state.hgtTxtBanner = $(selectors.layout.textBanner).parent().height();

    // Lógica Sticky Header
    if (window.scrollY >= ((state.hgtFilter - state.hgtTxtBanner) + state.hgtNavbar - CONFIG.headerGap)) {

        if (!$(selectors.layout.searchFilter).is(':hidden')) {
            $(selectors.layout.filterContent).css({
                'top': -((state.hgtFilter - state.hgtTxtBanner) + state.hgtNavbar - CONFIG.headerGap),
                'position': 'fixed',
                'width': '100%'
            });
            $(selectors.layout.bgFilter).css('opacity', 0);

            let hgt_selector_filter = $(selectors.layout.searchFilter).outerHeight();
            let hgt_txt_filter = $(selectors.layout.searchFilter).outerHeight(true);

            $(selectors.tags.container).css({
                'top': ((state.hgtFilter - hgt_txt_filter) - hgt_selector_filter),
                'position': 'fixed'
            });

            let hgt_search_filter_tags = ($(selectors.tags.container).outerHeight() > 0) ? $(selectors.tags.container).outerHeight() : 0;
            $(selectors.layout.scrollControl).css('top', (state.hgtNavbar + state.hgtFilter + hgt_search_filter_tags));
        }

    } else {
        $(selectors.layout.filterContent).removeAttr('style');
        $(selectors.tags.container).removeAttr('style');
        $(selectors.layout.scrollControl).removeAttr('style');

        if (typeof device !== 'undefined' && device === 1 && ((window.innerHeight + ((state.hgtFilter - state.hgtTxtBanner) + state.hgtNavbar)) < document.body.scrollHeight)) {
            $(selectors.body).attr('style', 'height: ' + (window.innerHeight + ((state.hgtFilter - state.hgtTxtBanner) + state.hgtNavbar)) + 'px !important');
        } else {
            $(selectors.body).removeAttr('style');
        }

        $(selectors.layout.bgFilter).css('opacity', (1 - (window.scrollY / 100)));
    }
}

// ==========================================
// LÓGICA DE FILTRADO (CORE)
// ==========================================

/**
 * Renderiza las etiquetas (pills) usando Templates HTML
 */
function renderFilterTags() {
    const $container = $(selectors.tags.container);
    const $preview = $(selectors.tags.preview);
    const tplPill = document.querySelector(selectors.templates.pill);
    const tplWrapper = document.querySelector(selectors.templates.deleteAll);

    if (!tplPill || !tplWrapper) {
        console.error("Templates de filtros no encontrados en el DOM");
        return;
    }

    let hasTags = false;

    // Crear el contenedor principal (Delete All Wrapper)
    // Clonamos el template wrapper
    const wrapperClone = tplWrapper.content.cloneNode(true);
    const $pillsContainer = $(wrapperClone).find('.pills-container');

    // Iterar filtros y crear pills
    CONFIG.filterKeys.forEach(key => {
        let $input = $(selectors.inputs[key]);
        let val = $input.val();

        if (val && val !== "" && (!Array.isArray(val) || val.length > 0)) {
            hasTags = true;
            let text = $input.find("option:selected").text();

            // Clonar pill individual
            let pillNode = tplPill.content.cloneNode(true);

            // Rellenar datos
            pillNode.querySelector('.pill-text').textContent = text;
            // Asignar data-target al botón de cerrar
            let closeBtn = pillNode.querySelector('.btn-close-pill');
            closeBtn.setAttribute('data-target', selectors.inputs[key]);

            // Añadir al contenedor de pills (usando append de DOM nativo sobre el fragmento jQuery)
            // Nota: $(wrapperClone) devuelve un fragmento, buscamos el div dentro
            wrapperClone.querySelector('.pills-container').appendChild(pillNode);
        }
    });

    // Renderizar o limpiar
    if (hasTags) {
        $preview.empty().append(wrapperClone); // Insertamos el clon completo
        if ($container.is(':hidden')) {
            $container.stop().slideDown(300);
        }
    } else {
        $container.stop().slideUp(300, function() {
            $preview.empty();
        });
    }
}

function triggerFilter(reset = true) {
    const scrollDuration = CONFIG.searchDebounceTime; // Tiempo que tarda en subir (ms)

    if (reset) {
        state.currentOffset = 0;
        state.allLoaded = false;
        showSkeletons(CONFIG.itemsPerLoad, false);
        $('html, body').animate({ scrollTop: 0 }, 500);
    } else {
        if (state.isLoading || state.allLoaded) return;
        showSkeletons(CONFIG.itemsPerLoad, true);
    }

    state.isLoading = true;
    updateURLWithFilters();

    if (reset) {
        // Si es un filtro nuevo, esperamos a que termine el scroll para mostrar/animar los tags
        setTimeout(function() {
            renderFilterTags();
        }, scrollDuration);
    } else {
        // Si es scroll infinito, renderizamos inmediato (normalmente no cambian tags aquí)
        renderFilterTags();
    }

    var data = {
        products: $(selectors.inputs.products).val(),
        formats: $(selectors.inputs.formats).val(),
        devices: $(selectors.inputs.devices).val(),
        countries: $(selectors.inputs.countries).val(),
        search: $(selectors.inputs.search).val(),
        offset: state.currentOffset,
        limit: CONFIG.itemsPerLoad
    };

    const requestPromise = $.ajax({
        url: ajaxUrlCatalog,
        type: 'POST',
        data: data
    });

    const delayPromise = new Promise(resolve => setTimeout(resolve, 2000));

    Promise.all([requestPromise, delayPromise])
        .then(([response, _]) => {
            $(selectors.containerCards).find('.skeleton-wrapper').remove();

            if (reset) {
                $(selectors.containerCards).html(response.creatives);
                if (response.count === 0) state.allLoaded = true;

                // Solo actualizamos opciones si estamos reseteando (filtro nuevo)
                updateFilterOptionsVisibility(response.availableOptions);
            } else {
                $(selectors.containerCards).append(response.creatives);
                if (response.count === 0 || response.count < CONFIG.itemsPerLoad) {
                    state.allLoaded = true;
                }
            }

            state.currentOffset += CONFIG.itemsPerLoad;
            state.isLoading = false;
        })
        .catch((error) => {
            console.error("Error in filter:", error);
            $(selectors.containerCards).find('.skeleton-wrapper').remove();
            state.isLoading = false;
        });
}

/**
 * Muestra/Oculta opciones de los selects basándose en los resultados disponibles
 */
function updateFilterOptionsVisibility(availableOptions) {
    // Si es null (sin filtros activos), mostramos todo
    if (!availableOptions) {
        $(selectors.inputs.products).find('option').prop('disabled', false).show();
        $(selectors.inputs.formats).find('option').prop('disabled', false).show();
        $(selectors.inputs.devices).find('option').prop('disabled', false).show();
        $(selectors.inputs.countries).find('option').prop('disabled', false).show();

        return;
    }

    // Helper para procesar cada input
    const processInput = (selector, validValues) => {
        let $select = $(selector);

        // Iteramos todas las opciones (menos el placeholder vacío)
        $select.find('option').each(function() {
            let $opt = $(this);
            let val = $opt.val();

            if (!val) return; // Saltamos el prompt "Industry", "Country", etc.

            // Convertimos a string para comparar seguramente (IDs vs strings)
            // validValues puede traer integers, val es string
            let isValid = validValues.some(v => String(v) === String(val));

            if (isValid) {
                $opt.prop('disabled', false).show();
            } else {
                // Opción A: Deshabilitar (Grisáceo, pero visible) -> Mejor UX para que sepan que existe
                $opt.prop('disabled', true);

                // Opción B: Ocultar (Desaparece de la lista) -> Lo que pediste
                //$opt.hide().prop('disabled', true);
            }
        });
    };

    // Aplicamos a cada filtro mapeando con las claves que devuelve el PHP
    if(availableOptions.products) processInput(selectors.inputs.products, availableOptions.products);
    if(availableOptions.formats) processInput(selectors.inputs.formats, availableOptions.formats);
    if(availableOptions.devices) processInput(selectors.inputs.devices, availableOptions.devices);
    if(availableOptions.countries) processInput(selectors.inputs.countries, availableOptions.countries);
}

function handleBackToTopVisibility() {
    let $btn = $(selectors.layout.backToTop);

    // Si el scroll supera 300px
    if ($(window).scrollTop() > 300) {
        // Si ya está visible o animándose hacia visible, no hacemos nada (evitamos reiniciar el efecto).
        if ($btn.is(':hidden')) {
            $btn.stop(true, false).fadeIn(300);
        }
    } else {
        // Solo aplicamos fadeOut si el botón está VISIBLE.
        if ($btn.is(':visible')) {
            $btn.stop(true, false).fadeOut(300);
        }
    }
}

// ==========================================
// EVENT LISTENER
// ==========================================

// --- Búsqueda (UI) ---
$(document).on('click', selectors.search.btnOpen, function (e) {
    e.preventDefault();

    setPositionSearchBarDesktop(true);

    $(this).stop().animate({ left: '+=25', opacity: '-=1' }, 300, function () {
        $(selectors.search.btnCancel).removeClass('d-lg-flex');
        $(selectors.search.container).removeClass('d-lg-none');

        $(selectors.search.container).stop().animate({
            left: '-=' + CONFIG.transitionSearchWidth,
            opacity: '+=1',
        }, 300, function () {
            $(selectors.search.btnCancel).addClass('d-lg-flex');
        });

        $(selectors.inputs.search).focus();
    });
});

$(document).on('click', selectors.search.btnCancel, function (e) {
    e.preventDefault();

    $(this).addClass('d-none').removeClass('d-lg-flex');

    $(selectors.search.container).stop().animate({
        left: '+=' + CONFIG.transitionSearchWidth,
        opacity: '-=1',
    }, 300, function () {
        $(selectors.search.container).addClass('d-lg-none');

        $(selectors.search.btnOpen).css({'visibility': 'visible'});
        $(selectors.search.btnOpen).stop().animate({
            left: '-=25',
            opacity: '+=1',
        }, 300, function () {
            $(selectors.inputs.search).val('');
            triggerFilter(true);
        });
    });
});

$(document).on('keydown', function(e) {
    if (e.key === "Escape" || e.keyCode === 27) {
        if (!$(selectors.search.container).hasClass('d-lg-none')) {
            $(selectors.search.btnCancel).trigger('click');
            $(selectors.inputs.search).blur();
        }
    }
});

// --- Filtros (Debounce & Change) ---
$(selectors.inputs.search).on('keyup', function() {
    clearTimeout(state.searchTimeout);
    state.searchTimeout = setTimeout(function() {
        triggerFilter(true);
    }, CONFIG.searchDebounceTime);
});

// Generar selector compuesto para cambios en selects
const selectsSelector = CONFIG.filterKeys.map(k => selectors.inputs[k]).join(', ');
$(selectsSelector).on('change', function() {
    triggerFilter(true);
});

// --- Tags / Pills ---
$(document).on('click', selectors.tags.btnClose, function() {
    let targetSelector = $(this).data('target');
    $(targetSelector).val('');
    triggerFilter(true);
});

$(document).on('click', selectors.tags.btnDeleteAll, function(e) {
    e.preventDefault();

    CONFIG.filterKeys.forEach(k => $(selectors.inputs[k]).val(''));
    triggerFilter(true);
});

// --- Back to Top ---
$(document).on('click', selectors.layout.backToTop, function() {
    $('html, body').animate({ scrollTop: 0 }, 500);
});

// --- Scroll Infinito & Sticky ---
$(window).on('scroll', function() {
    // Manejo visual del filtro sticky
    handleStickyFilterScroll();

    // Manejo del botón Back to Top
    handleBackToTopVisibility();

    // Detección de final de página para carga
    let pos = window.innerHeight + window.scrollY;

    // console.log("Scroll Pos:", Math.ceil(pos), "Body Height:", document.body.scrollHeight);

    // Verificamos si existe la variable global y si es true.
    // Si estamos en Favorite details, NO ejecutamos el triggerFilter(false).
    let isDetailView = (typeof isFavoritesDetail !== 'undefined' && isFavoritesDetail === true);

    if (!isDetailView && Math.ceil(pos) >= document.body.scrollHeight) {
        triggerFilter(false);
    }
});

// --- Resize ---
$(window).on('resize', function() {
    if (!$(selectors.search.container).hasClass('d-lg-none')) {
        setPositionSearchBarDesktop(false);
    }
});

// ==========================================
// INICIALIZACIÓN
// ==========================================

$(document).ready(function() {
    if (typeof hgt_navbar !== 'undefined') {
        state.hgtNavbar = hgt_navbar;
    }

    if (typeof initialAvailableOptions !== 'undefined' && initialAvailableOptions !== null) {
        updateFilterOptionsVisibility(initialAvailableOptions);
    }

    let params = new URLSearchParams(window.location.search);

    if (params.toString()) {
        CONFIG.filterKeys.forEach(key => {
            if(params.has(key)) {
                let slugFromUrl = params.get(key);
                let $select = $(selectors.inputs[key]);
                let $option = $select.find(`option[data-slug="${slugFromUrl}"]`);

                if ($option.length > 0) {
                    $select.val($option.val());
                } else {
                    // Fallback: Si no hay slug match
                    $select.val(slugFromUrl);
                }
            }
        });

        if (params.has('search')) $(selectors.inputs.search).val(params.get('search'));

        renderFilterTags();

        // Estado inicial de la búsqueda si viene por URL
        if (params.has('search') && params.get('search') !== '') {
            $(selectors.search.container).removeClass('d-lg-none');
            $(selectors.search.btnOpen).css({opacity: 0, visibility: 'hidden'});
            $(selectors.search.btnCancel).removeClass('d-none').addClass('d-flex');
            setPositionSearchBarDesktop(false);
        }
    }
});