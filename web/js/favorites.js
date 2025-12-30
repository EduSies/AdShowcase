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
        list: '.list-favorites-screen', // Lista
        create: '.create-list-favorite-screen', // Crear
        scrollContainer: '.list-favorites-screen .overflow-auto > div' // Donde inyectamos el HTML
    },
    sections: {
        mainLists: '.lists-favorites-section' // Contenedor principal que envuelve todas las cards de listas
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

var favConfig = window.favConfig || {
    closeOnCreate: false, // ¿Cerrar dropdown al crear una lista nueva?
    closeOnToggle: false // ¿Cerrar dropdown al añadir/quitar una crea?
};

// ==========================================
// ACCIONES DE LISTAS FAVORITOS
// ==========================================

const listActionSelectors = {
    // Selector del contenedor principal del menú desplegable de Bootstrap
    dropdownMenu: '.dropdown-menu',
    listName: '#list-name',
    breadcrumbsListName: '#breadcrumbs-list-name',
    // Selectores para las diferentes "pantallas" o vistas dentro del dropdown
    screens: {
        main: '.list-actions-screen', // Menú principal (Editar, Mover, Eliminar)
        edit: '.edit-name-list-favorite-screen', // Formulario para renombrar la lista
        move: '.move-list-favorite-screen' // Lista de opciones para mover a otra lista
    },
    // Botones que disparan la navegación entre pantallas
    triggers: {
        edit: '.edit-name-list', // Botón "Edit name"
        move: '.move-favorites-list', // Botón "Move to another list"
        back: '.back-to-list-btn', // Botón "Atrás"
        saveEdit: '.edit-name-list-favorite', // Botón Guardar Nombre
        saveMove: '.move-to-list-favorite', // Botón Mover Lista
        saveDelete: '.delete-favorites-list', // Botón Eliminar Lista
    },
    buttons: {
        createList: '#create-list-page-dropdown',
    },
    // Inputs y campos de formulario
    inputs: {
        editName: 'input[name="input_edit_name_list"]', // El input de texto donde se escribe el nuevo nombre
        newListName: 'input[name="new_list_name"]',
    }
};

/**
 * Función genérica para cambiar de pantalla dentro del dropdown
 */
function switchListScreen($menu, targetScreenSelector) {
    // Buscamos la pantalla actualmente visible
    let $currentScreen = $menu.find('> div:visible');
    let $targetScreen = $menu.find(targetScreenSelector);

    $currentScreen.animate({ marginLeft: '-100%', opacity: 0 }, 200, function() {
        $currentScreen.hide();
        $currentScreen.css('marginLeft', '0');

        $targetScreen.css({ marginLeft: '100%', display: 'block', opacity: 0 })
            .animate({ marginLeft: '0%', opacity: 1 }, 200, function() {
                if (targetScreenSelector === listActionSelectors.screens.edit) {
                    $targetScreen.find(listActionSelectors.inputs.editName).focus();
                }
            });
    });
}

/**
 * Volver a la pantalla principal
 */
function backToMainScreen($menu) {
    let $currentScreen = $menu.find('> div:visible');
    let $mainScreen = $menu.find(listActionSelectors.screens.main);

    // Buscamos la pantalla de edición y su input
    let $editScreen = $menu.find(listActionSelectors.screens.edit);
    let $input = $editScreen.find(listActionSelectors.inputs.editName);

    // Si encontramos el input, restauramos su valor original
    if ($input.length > 0) {
        let originalName = $input.data('original-name');
        // Restauramos el valor y limpiamos errores
        $input.val(originalName);
        $input.removeClass('is-invalid');
    }

    $currentScreen.animate({ marginLeft: '100%', opacity: 0 }, 200, function() {
        $currentScreen.hide();
        $mainScreen.css({ marginLeft: '-100%', display: 'block', opacity: 0 })
            .animate({ marginLeft: '0%', opacity: 1 }, 200);
    });
}

// --- EVENTOS ---

// Clic en "Edit name"
$(document).on('click', listActionSelectors.triggers.edit, function(e) {
    e.preventDefault();
    e.stopPropagation();

    let $menu = $(this).closest(listActionSelectors.dropdownMenu);
    switchListScreen($menu, listActionSelectors.screens.edit);
});

// Clic en "Move to another list"
$(document).on('click', listActionSelectors.triggers.move, function(e) {
    e.preventDefault();
    e.stopPropagation();

    let $menu = $(this).closest(listActionSelectors.dropdownMenu);
    switchListScreen($menu, listActionSelectors.screens.move);
});

// Clic en "Atrás"
$(document).on('click', listActionSelectors.triggers.back, function(e) {
    e.preventDefault();
    e.stopPropagation();

    let $menu = $(this).closest(listActionSelectors.dropdownMenu);
    backToMainScreen($menu);
});

// Al ABRIR el dropdown
$(document).on('shown.bs.dropdown', function (e) {
    let $toggle = $(e.target);

    // Verificamos si el dropdown que se abre es el de acciones de lista
    if ($toggle.hasClass('icon-favorite-actions') || $toggle.attr('id') === 'create-list-page-dropdown') {
        $('body').addClass('overflow-hidden');

        let $dropdownMenu = $toggle.next(listActionSelectors.dropdownMenu);
        $dropdownMenu.find(listActionSelectors.inputs.newListName).focus();
    }
});

// Al CERRAR el dropdown
$(document).on('hidden.bs.dropdown', function (e) {
    let $toggle = $(e.target);

    // Verificamos si el dropdown que se cierra es el de acciones de lista
    if ($toggle.hasClass('icon-favorite-actions') || $toggle.attr('id') === 'create-list-page-dropdown') {
        $('body').removeClass('overflow-hidden'); // Desbloqueamos el scroll

        let $menu = $toggle.next(listActionSelectors.dropdownMenu);
        $menu.find(listActionSelectors.inputs.newListName).val('');

        let $container = $toggle.closest(favSelectors.dropdown.container);
        let $input = $container.find(listActionSelectors.inputs.newListName);
        $input.removeClass('is-invalid');

        // Si el menú tiene nuestras pantallas internas, lo reseteamos
        if ($menu.find(listActionSelectors.screens.main).length > 0) {
            $menu.find('> div').hide();
            $menu.find(listActionSelectors.screens.main).show().css({ opacity: 1, marginLeft: 0 });
        }
    }
});

// ==========================================
// GUARDAR EDICIÓN DE NOMBRE DE LISTA + REFRESH
// ==========================================

$(document).on('click', listActionSelectors.triggers.saveEdit, function(e) {
    e.preventDefault();
    e.stopPropagation();

    let $btn = $(this);
    let listHash = $btn.data('list-hash');

    // Buscamos el input dentro de la pantalla de edición usando los selectores configurados
    let $container = $btn.closest(listActionSelectors.screens.edit);
    let $input = $container.find(listActionSelectors.inputs.editName);

    let newName = $input.val().trim();
    let originalName = $input.data('original-name');

    // Validaciones Frontend
    if (newName === "") {
        // Feedback visual si está vacío
        $input.addClass('is-invalid');
        if (window.swalDanger) swalDanger(swalFireHtmlEmptyList);
        return;
    }

    if (newName === originalName) {
        $input.addClass('is-invalid');
        if (window.swalDanger) swalDanger(swalFireHtmlRenameList);
        return;
    }

    // Loading
    let originalContent = $btn.html();
    let width = $btn.outerWidth();
    let height = $btn.outerHeight();
    let spinnerHtml = $(favSelectors.templates.spinner).html();

    $btn.css({
        'width': width,
        'height': height
    }).prop('disabled', true).html(spinnerHtml);

    $input.removeClass('is-invalid');

    // Petición AJAX al servidor
    $.ajax({
        url: ajaxUrlUpdateList,
        type: 'POST',
        dataType: 'json',
        data: {
            listHash: listHash,
            name: newName,
            isFavoritesDetail: (typeof isFavoritesDetail !== 'undefined') ? isFavoritesDetail : false
        },
        success: function(response) {
            if (response.success) {
                // Reemplazar TODA la sección de listas con el nuevo HTML renderizado
                $(favSelectors.sections.mainLists).html(response.html);

                if ($(listActionSelectors.listName).data('list-hash') === listHash) {
                    $(listActionSelectors.breadcrumbsListName).html(newName);
                    $(listActionSelectors.listName).html(newName);
                }

                if (window.swalSuccess) swalSuccess(response.message);
            } else {
                // Feedback de error
                if (window.swalDanger) swalDanger(response.message);
                $btn.prop('disabled', false).html(originalContent);
            }

            $('body').removeClass('overflow-hidden');
        },
        error: function() {
            if (window.swalDanger) swalDanger('Server error occurred');
            $btn.prop('disabled', false).html(originalContent);
        }
    });
});

// ==========================================
// MOVER LISTA
// ==========================================

$(document).on('click', listActionSelectors.triggers.saveMove, function(e) {
    e.preventDefault();
    e.stopPropagation();

    let $btn = $(this);
    let fromListHash = $btn.data('list-hash');
    let toListHash = $btn.data('to-list-hash');

    // Loading
    let originalContent = $btn.html();
    let width = $btn.outerWidth();
    let height = $btn.outerHeight();
    let spinnerHtml = $(favSelectors.templates.spinner).html();

    $btn.css({
        'width': width,
        'height': height
    }).prop('disabled', true).html(spinnerHtml);

    // Petición AJAX al servidor
    $.ajax({
        url: ajaxUrlMoveList,
        type: 'POST',
        dataType: 'json',
        data: {
            fromHash: fromListHash,
            toHash: toListHash,
            isFavoritesDetail: (typeof isFavoritesDetail !== 'undefined') ? isFavoritesDetail : false
        },
        success: function(response) {
            if (response.success) {
                $(favSelectors.sections.mainLists).html(response.html);
                if (window.swalSuccess) swalSuccess(response.message);
            } else {
                if (window.swalDanger) swalDanger(response.message);
                $btn.prop('disabled', false).html(originalContent);
            }

            $('body').removeClass('overflow-hidden');
        },
        error: function() {
            if (window.swalDanger) swalDanger('Server error occurred');
            $btn.prop('disabled', false).html(originalContent);
        }
    });
});

// ==========================================
// ELIMINAR LISTA
// ==========================================

$(document).on('click', listActionSelectors.triggers.saveDelete, function(e) {
    e.preventDefault();
    e.stopPropagation();

    let $btn = $(this);
    let listHash = $btn.data('list-hash');
    let listName = $btn.data('list-name');

    // Confirmación SweetAlert
    swalFire({
        title: swalFireTitleDeleteList.toString(),
        html: (swalFireHtmlDeleteList).replace('{NAME_LIST}', '<b>' + listName + '</b>').toString(),
        showCancelButton: true,
        confirmButtonText: swalFireConfirmButton.toString(),
        cancelButtonText: swalFireCancelButton.toString(),
        allowOutsideClick: false,
    }).then((result) => {
        if (result.isConfirmed) {

            $.ajax({
                url: ajaxUrlDeleteList,
                type: 'POST',
                dataType: 'json',
                data: {
                    listHash: listHash,
                    isFavoritesDetail: (typeof isFavoritesDetail !== 'undefined') ? isFavoritesDetail : false
                },
                success: function(response) {
                    if (response.success) {
                        // Si estamos en la página de detalle de la lista borrada -> Redirigir
                        if (typeof isFavoritesDetail !== 'undefined' && isFavoritesDetail && $(listActionSelectors.listName).data('list-hash') === listHash) {
                            window.location.href = urlFavoritesList;
                            return;
                        }

                        $(favSelectors.sections.mainLists).html(response.html);
                        if (window.swalSuccess) swalSuccess(response.message);
                    } else {
                        if (window.swalDanger) swalDanger(response.message);
                    }
                },
                error: function() {
                    if (window.swalDanger) swalDanger('Server error occurred');
                }
            });
        }
    });
});

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
    let $input = $screen.find(favSelectors.inputs.listName);

    let name = $input.val();
    let creativeHash = $btn.data('creative-hash');

    // Validaciones Frontend
    if (name.trim() === "") {
        // Feedback visual si está vacío
        $input.addClass('is-invalid');
        if (window.swalDanger) swalDanger(swalFireHtmlEmptyList);
        return;
    }

    // Loading
    let originalContent = $btn.html();
    let width = $btn.outerWidth();
    let height = $btn.outerHeight();
    let spinnerHtml = $(favSelectors.templates.spinner).html();

    $btn.css({
        'width': width,
        'height': height
    }).prop('disabled', true).html(spinnerHtml);

    $input.removeClass('is-invalid');

    $.ajax({
        url: ajaxUrlCreateList,
        type: 'POST',
        dataType: 'json',
        data: { name: name },
        success: function(response) {
            if (response.success) {
                if (creativeHash) {
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

                                if (favConfig.closeOnCreate) {
                                    closeFavDropdown($dropdownMenu);
                                } else {
                                    // Cerramos el dropdown
                                    $dropdownMenu.closest(favSelectors.dropdown.container).find(favSelectors.dropdown.trigger).trigger('click');
                                }

                                if(window.swalSuccess) swalSuccess(response.message);
                            }
                            // Restaurar botón
                            $btn.prop('disabled', false).html(originalContent);
                        },
                        error: function() {
                            if (window.swalDanger) swalDanger('Server error occurred');
                            $btn.prop('disabled', false).html(originalContent);
                        }
                    });
                } else {
                    $(favSelectors.sections.mainLists).html(response.html);
                    if(window.swalSuccess) swalSuccess(response.message);
                    $input.val('');
                    let $dropdownMenu = $btn.closest(favSelectors.dropdown.container);
                    $dropdownMenu.find(listActionSelectors.buttons.createList).trigger('click');
                    // Restaurar botón
                    $btn.prop('disabled', false).html(originalContent);
                }
            } else {
                if(window.swalDanger) swalDanger(response.message);
                $input.addClass('is-invalid');
                $btn.prop('disabled', false).html(originalContent);
            }
        },
        error: function() {
            if (window.swalDanger) swalDanger('Server error occurred');
            $btn.prop('disabled', false).html(originalContent);
        }
    });
});

// --- TOGGLE ITEM (AÑADIR/QUITAR) ---
$(document).on('click', favSelectors.buttons.toggleItem, function(e) {
    e.preventDefault();
    e.stopPropagation();

    let $btn = $(this);
    let $dropdownMenu = $btn.closest(favSelectors.dropdown.menu);
    let $card = $btn.closest(favSelectors.card);

    let listHash = $btn.data('list-hash');
    let action = $btn.data('action');
    let creativeHash = $dropdownMenu.find(favSelectors.buttons.saveList).data('creative-hash');

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
                $dropdownMenu.find(favSelectors.screens.scrollContainer).html(response.html);

                // Actualizar el icono de la estrella en la card
                updateCardStar($card, response.isFavorite);

                if (favConfig.closeOnToggle) {
                    closeFavDropdown($dropdownMenu);
                }

                if(window.swalSuccess) swalSuccess(response.message);

            } else {
                if(window.swalDanger) swalDanger(response.message);
            }
            // Restaurar botón
            $btn.prop('disabled', false).html(originalContent);
        },
        error: function() {
            if (window.swalDanger) swalDanger('Server error occurred');
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

/**
 * Cierra el dropdown buscando la card padre para encontrar el trigger correcto.
 */
function closeFavDropdown($dropdownMenu) {
    $dropdownMenu.css('pointer-events', 'none');

    $dropdownMenu.stop().animate({ opacity: 0 }, 800, function() {
        $dropdownMenu.closest(favSelectors.dropdown.container).find(favSelectors.dropdown.trigger).trigger('click');

        $(this).css({
            'opacity': '',
            'pointer-events': ''
        });
    });
}