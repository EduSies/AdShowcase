// Configuración de selectores para Favoritos
const favSelectors = {
    dropdown: {
        container: '.dropdown', // El contenedor de Bootstrap
        menu: '.dropdown-menu', // El menú desplegable
        trigger: '.icon-favorite-card', // El icono/botón que abre el menú
        contentWrapper: '.dropdown-content-wrapper',
        loader: '.content-loader'
    },
    screens: {
        list: '.list-favorites-screen', // Pantalla 1: Lista
        create: '.create-list-favorite-screen', // Pantalla 2: Crear
        scrollContainer: '.list-favorites-screen .overflow-auto > div' // Donde inyectamos el HTML
    },
    buttons: {
        goToCreate: '.create-list-favorite-btn', // Botón "Create list +"
        backToList: '.back-to-list-btn', // Botón "< Create list"
        saveList: '.save-new-list-btn', // Botón "Save"
        toggleItem: '.toggle-list-btn' // Botón "Add / Added" de cada item
    },
    inputs: {
        listName: 'input.new-list-input' // Input para el nombre de la nueva lista
    },
    templates: {
        spinner: '#spinner-template' // ID del template del spinner de carga
    },
    card: '.creative-card', // Selector de la tarjeta contenedora, la card
    activeClass: 'is-active' // Clase CSS que se añade a la card para mantener el overlay visible al abrir el menú
};

// --- Eventos de Navegación ---
$(document).on('click', favSelectors.buttons.goToCreate, function(e) {
    e.preventDefault();
    e.stopPropagation();

    let $dropdown = $(this).closest(favSelectors.dropdown.menu);
    let $listScreen = $dropdown.find(favSelectors.screens.list);
    let $createScreen = $dropdown.find(favSelectors.screens.create);

    $listScreen.animate({ marginLeft: '-100%', opacity: 0 }, 200, function() {
        $listScreen.hide();
        $createScreen.css({ marginLeft: '100%', display: 'block', opacity: 0 })
            .animate({ marginLeft: '0%', opacity: 1 }, 200, function() {
                $createScreen.find(favSelectors.inputs.listName).focus();
            });
    });
});

$(document).on('click', favSelectors.buttons.backToList, function(e) {
    e.preventDefault();
    e.stopPropagation();

    let $dropdown = $(this).closest(favSelectors.dropdown.menu);
    let $listScreen = $dropdown.find(favSelectors.screens.list);
    let $createScreen = $dropdown.find(favSelectors.screens.create);

    $createScreen.animate({ marginLeft: '100%', opacity: 0 }, 200, function() {
        $createScreen.hide(); $createScreen.find(favSelectors.inputs.listName).val('');
        $listScreen.css({ marginLeft: '-100%', display: 'block', opacity: 0 })
            .animate({ marginLeft: '0%', opacity: 1 }, 200);
    });
});

// Detectar apertura del dropdown de Bootstrap
$(document).on('show.bs.dropdown', function (e) {
    let $toggle = $(e.target);

    if (!$toggle.hasClass('icon-favorite-card')) return;

    let creativeHash = $toggle.data('creative-hash');
    let $dropdownMenu = $toggle.next(favSelectors.dropdown.menu);
    let $wrapper = $dropdownMenu.find(favSelectors.dropdown.contentWrapper);
    let $loader = $dropdownMenu.find(favSelectors.dropdown.loader);

    $toggle.closest(favSelectors.card).addClass(favSelectors.activeClass);
    $('body').addClass('overflow-hidden');

    // Si ya tiene contenido cargado, no hacemos nada
    if ($wrapper.children().length > 0) return;

    // Si el contenedor del loader está vacío, inyectamos el template
    if ($loader.children().length === 0) {
        let tpl = document.querySelector(favSelectors.templates.spinner);
        if (tpl) {
            // Clonamos el contenido del template y lo añadimos
            $loader.append(tpl.content.cloneNode(true));
        }
    }

    // Mostrar loader, ocultar wrapper
    $loader.show();
    $wrapper.hide();

    // Petición AJAX
    $.ajax({
        url: ajaxUrlGetDropdown,
        type: 'POST',
        data: { creativeHash: creativeHash },
        success: function (html) {
            $wrapper.html(html).show();
            $loader.hide();
        },
        error: function () {
            if(window.swalDanger) swalDanger('Error loading list');
        }
    });
});

$(document).on('hidden.bs.dropdown', function (e) {
    let $toggle = $(e.target);
    if (!$toggle.hasClass('icon-favorite-card')) return;

    $toggle.closest(favSelectors.card).removeClass(favSelectors.activeClass);
    $('body').removeClass('overflow-hidden');

    let $dropdownMenu = $toggle.next(favSelectors.dropdown.menu);
    $dropdownMenu.find(favSelectors.dropdown.contentWrapper).empty();
    $dropdownMenu.find(favSelectors.dropdown.loader).show();
});

// --- GUARDAR NUEVA LISTA ---
$(document).on('click', favSelectors.buttons.saveList, function(e) {
    e.preventDefault();

    let $btn = $(this);
    let $screen = $btn.closest(favSelectors.screens.create);
    let $dropdownMenu = $btn.closest(favSelectors.dropdown.menu);
    let $input = $screen.find(favSelectors.inputs.listName);

    let name = $input.val();
    let creativeHash = $btn.data('creative-hash');

    if(name.trim() === "") return;

    // Loading
    let originalContent = $btn.html();
    let width = $btn.outerWidth();
    let height = $btn.outerHeight();
    let spinnerHtml = $(favSelectors.templates.spinner).html();

    $btn.css({
        'width': width,
        'height': height
    }).prop('disabled', true).html(spinnerHtml);

    $.ajax({
        url: ajaxUrlCreateList,
        type: 'POST',
        dataType: 'json',
        data: { name: name },
        success: function(response) {
            if (response.success) {
                // Lista creada. Ahora añadimos el ítem.
                $.ajax({
                    url: ajaxUrlToggleItem,
                    type: 'POST',
                    data: {
                        listHash: response.listHash,
                        creativeHash: creativeHash,
                        action: 'add'
                    },
                    success: function(resToggle) {
                        if(resToggle.success) {
                            // Actualizar lista visualmente
                            let $dropdownMenu = $btn.closest(favSelectors.dropdown.menu);
                            $dropdownMenu.find(favSelectors.screens.scrollContainer).html(resToggle.html);

                            // Actualizar estrella
                            let $card = $btn.closest(favSelectors.card);
                            updateCardStar($card, resToggle.isFavorite);

                            // Volver atrás
                            $dropdownMenu.find(favSelectors.buttons.backToList).trigger('click');

                            if(window.swalSuccess) swalSuccess(response.message);
                        }
                        $btn.prop('disabled', false).html(originalContent);
                    },
                    error: function() {
                        $btn.prop('disabled', false).html(originalContent);
                    }
                });
            } else {
                if(window.swalDanger) swalDanger(response.message);
                $btn.prop('disabled', false).html(originalContent);
            }
        },
        error: function() {
            $btn.prop('disabled', false).html(originalContent);
        }
    });
});

// --- TOGGLE ITEM (AÑADIR/QUITAR) ---
$(document).on('click', favSelectors.buttons.toggleItem, function(e) {
    e.preventDefault();
    e.stopPropagation();

    let $btn = $(this);
    let $dropdown = $btn.closest(favSelectors.dropdown.menu);
    let $card = $btn.closest(favSelectors.card);

    let listHash = $btn.data('list-hash');
    let action = $btn.data('action');
    let creativeHash = $dropdown.find(favSelectors.buttons.saveList).data('creative-hash');

    // Loading
    let originalContent = $btn.html();
    let width = $btn.outerWidth();
    let height = $btn.outerHeight();
    let spinnerHtml = $(favSelectors.templates.spinner).html();

    $btn.css({
        'width': width,
        'height': height
    }).prop('disabled', true).html(spinnerHtml);

    $.ajax({
        url: ajaxUrlToggleItem,
        type: 'POST',
        dataType: 'json',
        data: {
            listHash: listHash,
            creativeHash: creativeHash,
            action: action
        },
        success: function(response) {
            if (response.success) {
                // Esto actualiza imágenes, textos, estados de botones y añade/quita filas automáticamente
                $dropdown.find(favSelectors.screens.scrollContainer).html(response.html);

                // Actualizar el icono de la estrella en la card
                updateCardStar($card, response.isFavorite);

                if(window.swalSuccess) swalSuccess(response.message);

            } else {
                if(window.swalDanger) swalDanger(response.message);
            }
            // Restaurar botón
            $btn.prop('disabled', false).html(originalContent);
        },
        error: function() {
            $btn.prop('disabled', false).html(originalContent);
        }
    });
});

/**
 * Actualiza el icono de la estrella de la tarjeta.
 * @param {jQuery} $card El elemento .creative-card
 * @param {boolean} isFavorite True si debe estar rellena, False si vacía
 */
function updateCardStar($card, isFavorite) {
    // Buscamos el botón trigger del dropdown dentro de la card
    let $starBtn = $card.find(favSelectors.dropdown.trigger);

    // Buscamos el icono <i> dentro del botón
    let $icon = $starBtn.find('i');

    if (isFavorite) {
        $icon.removeClass('bi-star').addClass('bi-star-fill');
    } else {
        $icon.removeClass('bi-star-fill').addClass('bi-star');
    }
}