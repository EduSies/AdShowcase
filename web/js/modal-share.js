// Configuración centralizada de selectores para el Modal de Compartir
const shareSelectors = {
    modal: {
        id: '#shareModal', // ID principal del modal de Bootstrap
        triggerCard: '.creative-card' // Clase de la tarjeta (card) que disparó el modal
    },
    steps: {
        config: '#shareConfigStep', // Paso 1: Pantalla de configuración (TTL, Vistas máximas)
        result: '#shareResultStep' // Paso 2: Pantalla de resultados (QR, Enlace generado)
    },
    inputs: {
        // Inputs ocultos (hidden) que almacenan los datos de la creatividad actual
        hiddenTitle: '#hidden-creative-title',
        hiddenFormat: '#hidden-creative-format',
        hiddenAgency: '#hidden-creative-agency',
        hiddenHash: '#hidden-shared-creative-hash',

        url: '#shareInputUrl', // Input visible (readonly) donde se muestra el enlace generado
        ttl: '#shareTtl', // Select de "Expires in". IMPORTANTE: Se usa para devolver el foco al volver del paso 2 al 1
        maxUses: '#shareMaxViews', // Input de máximas views de una creative

        // Inputs ocultos con textos traducidos (Yii::t) para construir mensajes de WhatsApp/Email
        msgBase: '#t-share-message',
        subjectBase: '#t-share-subject',
        emailDest: '#shareInputEmailDest'
    },
    buttons: {
        generate: '#btnGenerateLink', // Botón para enviar la petición AJAX y generar el enlace
        reset: '#btnResetShare', // Botón "Generate new link" (Resetea la vista al paso 1)
        copy: '#btnCopyLink', // Botón para copiar la URL al portapapeles
        download: '#btnDownloadComposite', // Botón para descargar la imagen compuesta (Canvas + QR)
        whatsapp: '#btnShareWhatsapp', // Enlace dinámico para compartir en WhatsApp
        mail: '#btnShareMail', // Enlace dinámico para compartir por Email
        sendEmail: '#btnSendEmailTrigger'
    },
    elements: {
        qrImage: '#shareQrImage', // Etiqueta <img> donde se carga el QR
        spinner: '#qrSpinner', // Spinner de carga que se muestra mientras llega el QR
        copyMessage: '#copySuccessMessage', // Mensaje temporal de éxito ("Link copied!")
        fallbackFocus: '#control-scroll-filter' // Elemento del DOM al que devolver el foco si se cierra el modal (Accesibilidad)
    }
};

$(document).ready(function() {
    const shareModalEl = document.querySelector(shareSelectors.modal.id);

    if (shareModalEl) {

        // --- ABRIR EL MODAL ---
        shareModalEl.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;

            // Capturar datos del botón disparador
            const currentHash = button.getAttribute('data-creative-hash');
            const title = button.getAttribute('data-creative-title');
            const format = button.getAttribute('data-creative-format');
            const agency = button.getAttribute('data-creative-agency');

            // Guardarlos en inputs ocultos
            $(shareSelectors.inputs.hiddenTitle).val(title);
            $(shareSelectors.inputs.hiddenFormat).val(format);
            $(shareSelectors.inputs.hiddenAgency).val(agency);
            $(shareSelectors.inputs.hiddenHash).val(currentHash);

            // Resetear UI
            $(shareSelectors.steps.config).removeClass('d-none');
            $(shareSelectors.steps.result).addClass('d-none');

            // Estado inicial del QR (Spinner visible, imagen oculta)
            $(shareSelectors.elements.spinner).removeClass('d-none');
            $(shareSelectors.elements.qrImage).addClass('d-none');
        });

        // --- CERRAR EL MODAL ---
        shareModalEl.addEventListener('hidden.bs.modal', function () {
            $(shareSelectors.inputs.emailDest).val('');
            // Devolver foco a un elemento seguro para accesibilidad
            const fallback = document.querySelector(shareSelectors.elements.fallbackFocus);
            if(fallback) {
                fallback.setAttribute('tabindex', '-1');
                fallback.focus({preventScroll:true});
            }
        });

        // --- GENERATE LINK ---
        $(shareSelectors.buttons.generate).on('click', function() {
            let $btn = $(this);

            let originalContent = $btn.html();
            let width = $btn.outerWidth();
            let height = $btn.outerHeight();
            let spinnerHtml = $(favSelectors.templates.spinner).html();

            $btn.css({
                'width': width,
                'height': height
            }).prop('disabled', true).html(spinnerHtml);

            $.ajax({
                url: ajaxUrlGenerateSharedLink,
                type: 'POST',
                data: {
                    creative_hash: $(shareSelectors.inputs.hiddenHash).val(),
                    ttl: $(shareSelectors.inputs.ttl).val(),
                    max_uses: $(shareSelectors.inputs.maxUses).val()
                },
                success: function(response) {
                    if (response.success) {
                        // Generar QR con la URL real devuelta por el servidor
                        const realUrl = response.url;
                        const qrUrl = 'https://quickchart.io/qr?size=300&margin=1&text=' + encodeURIComponent(realUrl);

                        processShareSuccess(realUrl, qrUrl);

                        if(window.swalSuccess) swalSuccess(response.message);
                    } else {
                        if(window.swalDanger) swalDanger(response.message);
                    }

                    $btn.prop('disabled', false).html(originalContent);
                },
                error: function() {
                    if (window.swalDanger) swalDanger('Server error occurred');
                    $btn.prop('disabled', false).html(originalContent);
                },
                complete: function() {
                    $btn.prop('disabled', false).html(originalContent);
                }
            });
        });

        // --- RESET ---
        $(shareSelectors.buttons.reset).on('click', function() {
            $(shareSelectors.steps.result).addClass('d-none');
            $(shareSelectors.steps.config).removeClass('d-none');

            $(shareSelectors.modal.id).focus();
            $(shareSelectors.inputs.emailDest).val('');
        });

        // --- COPY LINK ---
        $(shareSelectors.buttons.copy).on('click', function() {
            const copyText = document.querySelector(shareSelectors.inputs.url);

            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(copyText.value)
                    .then(showCopySuccess)
                    .catch(() => fallbackCopy(copyText));
            } else {
                fallbackCopy(copyText);
            }
        });

        // --- DOWNLOAD COMPOSITE IMAGE ---
        $(shareSelectors.buttons.download).on('click', function(e) {
            e.preventDefault();
            downloadCompositeImage();
        });
    }

    function processShareSuccess(url, qrImageSrc) {
        // Cambio de pantalla
        $(shareSelectors.steps.config).addClass('d-none');
        $(shareSelectors.steps.result).removeClass('d-none');

        // Asignar URL al input
        $(shareSelectors.inputs.url).val(url);

        // Cargar imagen QR
        const img = document.querySelector(shareSelectors.elements.qrImage);
        img.crossOrigin = "Anonymous"; // Necesario para pintar en Canvas después
        img.src = qrImageSrc;

        // Cuando la imagen cargue, ocultar spinner y mostrar imagen
        img.onload = function() {
            $(shareSelectors.elements.spinner).addClass('d-none');
            $(this).removeClass('d-none');
        };

        // Actualizar enlaces de compartir social
        const msgBase = $(shareSelectors.inputs.msgBase).val();
        const subjectBase = $(shareSelectors.inputs.subjectBase).val();

        $(shareSelectors.buttons.whatsapp).attr('href', "https://wa.me/?text=" + encodeURIComponent(msgBase + " " + url));
        $(shareSelectors.buttons.mail).attr('href', "mailto:?subject=" + encodeURIComponent(subjectBase) + "&body=" + encodeURIComponent(msgBase + ": " + url));
    }

    function downloadCompositeImage() {
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        const img = document.querySelector(shareSelectors.elements.qrImage);

        // Datos desde inputs ocultos
        const title = $(shareSelectors.inputs.hiddenTitle).val() || "Creative";
        const format = ($(shareSelectors.inputs.hiddenFormat).val() || "Format").toUpperCase();
        const agency = $(shareSelectors.inputs.hiddenAgency).val() || "Agency";

        // Obtener color principal de CSS
        const mainColor = getComputedStyle(document.documentElement).getPropertyValue('--main-color-1').trim();

        // Configuración Canvas
        const width = 600;
        const padding = 40;
        const qrSize = 400;
        const textStart = padding + qrSize + 20;
        const height = textStart + 150;

        canvas.width = width;
        canvas.height = height;

        // Fondo Blanco
        ctx.fillStyle = "#ffffff";
        ctx.fillRect(0, 0, width, height);

        // Dibujar QR
        const qrX = (width - qrSize) / 2;
        ctx.drawImage(img, qrX, padding, qrSize, qrSize);

        // Configurar Textos
        ctx.textAlign = "center";
        const centerX = width / 2;

        // FORMATO
        ctx.fillStyle = "#6c757d";
        ctx.font = "bold 18px system-ui";
        ctx.letterSpacing = "2px";
        let currentY = textStart + 20;
        ctx.fillText(format, centerX, currentY);

        // TÍTULO
        ctx.fillStyle = mainColor;
        ctx.font = "bold 28px system-ui";
        ctx.letterSpacing = "0px";
        currentY += 40;

        let displayTitle = title;
        if (title.length > 35) displayTitle = title.substring(0, 35) + '...';
        ctx.fillText(displayTitle, centerX, currentY);

        // AGENCIA
        ctx.fillStyle = "#6c757d";
        ctx.font = "20px system-ui";
        currentY += 35;
        ctx.fillText(agency, centerX, currentY);

        // Descargar
        try {
            const dataUrl = canvas.toDataURL("image/png");
            const link = document.createElement('a');
            link.download = 'qr-creative-share.png';
            link.href = dataUrl;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        } catch (err) {
            console.error("Could not generate image:", err);
        }
    }

    function fallbackCopy(inputElement) {
        inputElement.select();
        inputElement.setSelectionRange(0, 99999);

        try {
            document.execCommand('copy');
            showCopySuccess();
        } catch (err) {
            console.error('Error to copy', err);
        }
    }

    function showCopySuccess() {
        const $msg = $(shareSelectors.elements.copyMessage);
        const $modal = $(shareSelectors.modal.id);

        $msg.removeClass('d-none').hide().fadeIn(300);

        setTimeout(() => {
            $msg.fadeOut(300, function() {
                $(this).addClass('d-none');
            });
        }, 3000);

        setTimeout(() => $modal.focus(), 100);
    }

    $(shareSelectors.buttons.sendEmail).on('click', function() {
        const $btn = $(this);
        const email = $(shareSelectors.inputs.emailDest).val();
        const sharedUrl = $(shareSelectors.inputs.url).val();

        // Validar email básico
        if (!email || !email.includes('@')) {
            if(window.swalDanger) swalDanger(textSendShareEmailValidate);
            return;
        }

        // Datos visuales para el correo
        const title = $(shareSelectors.inputs.hiddenTitle).val();
        const format = $(shareSelectors.inputs.hiddenFormat).val();
        const agency = $(shareSelectors.inputs.hiddenAgency).val();

        // Generamos la URL del QR igual que en el frontend para mandarla al backend
        // Usamos QuickChart porque es accesible públicamente por el cliente de correo
        const qrUrl = 'https://quickchart.io/qr?size=300&margin=1&text=' + encodeURIComponent(sharedUrl);

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
            url: ajaxUrlSendShareEmail,
            type: 'POST',
            data: {
                email: email,
                url: sharedUrl,
                qr_src: qrUrl,
                title: title,
                format: format,
                agency: agency
            },
            success: function(response) {
                if (response.success) {
                    if(window.swalSuccess) swalSuccess(response.message);
                    $(shareSelectors.inputs.emailDest).val('');
                } else {
                    if(window.swalDanger) swalDanger(response.message);
                }
            },
            error: function() {
                if(window.swalDanger) swalDanger(textSendShareEmailError);
            },
            complete: function() {
                $btn.prop('disabled', false).html(originalContent);
            }
        });
    });
});