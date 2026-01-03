/**
 * Lógica para CropperJS v2.1.0
 */
document.addEventListener('DOMContentLoaded', function () {
    const elements = {
        fileInput: document.getElementById('thumbnail-file-input'),
        hiddenInput: document.getElementById('crop-data-input'),
        previewImage: document.getElementById('thumbnail-preview'),
        previewContainer: document.getElementById('preview-container'),
        cropModalEl: document.getElementById('crop-modal'),
        cropBtn: document.getElementById('btn-crop-save'),
        cropperImage: document.querySelector('cropper-image'),
        cropperSelection: document.querySelector('cropper-selection'),
        cropperShade: document.querySelector('cropper-shade')
    };

    if (!elements.fileInput || !elements.hiddenInput || !elements.cropModalEl) return;

    // Trigger para abrir el input file al hacer click en la preview
    if (elements.previewImage) {
        elements.previewImage.addEventListener('click', function() {
            elements.fileInput.click();
        });
    }

    const bs = window.bootstrap || bootstrap;
    const cropModal = new bs.Modal(elements.cropModalEl);
    let tempImageSrc = null; // Inicializamos a null explícitamente

    // Leemos los data-attributes del input file. Si no existen, usamos los defaults (Creative Banner)
    const getSettings = () => {
        return {
            aspectRatio: parseFloat(elements.fileInput.dataset.aspectRatio) || 1,
            width: parseInt(elements.fileInput.dataset.cropWidth) || 400,
            height: parseInt(elements.fileInput.dataset.cropHeight) || 400
        };
    };

    // Selección de archivo
    elements.fileInput.addEventListener('change', function (e) {
        const files = e.target.files;
        if (files && files.length > 0) {
            const file = files[0];

            if (!file.type.startsWith('image/')) {
                swalDanger('Please select a valid image file.');
                elements.fileInput.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function (evt) {
                tempImageSrc = evt.target.result;
                // No asignamos el src todavía, esperamos a que el modal se muestre
                cropModal.show();
            };

            reader.readAsDataURL(file);
        }
    });

    // Evento: Modal Visible
    elements.cropModalEl.addEventListener('shown.bs.modal', function () {
        if (!tempImageSrc || !elements.cropperImage) return;

        // Asignamos el source solo ahora que el modal es visible
        elements.cropperImage.src = tempImageSrc;

        if (elements.cropperSelection) {
            const settings = getSettings();

            // Configuración visual según Aspect Ratio
            if (settings.aspectRatio === 1) {
                // Modo Redondo (Avatar)
                elements.cropperSelection.classList.add('cropper-round');
                if (elements.cropperShade) elements.cropperShade.classList.add('cropper-round');
            } else {
                // Modo Rectangular (Banner)
                elements.cropperSelection.classList.remove('cropper-round');
                if (elements.cropperShade) elements.cropperShade.classList.remove('cropper-round');
            }

            // Pequeño delay para asegurar que el componente web ha renderizado la imagen interna
            setTimeout(() => {
                elements.cropperSelection.$center();

                // Aplicar Aspect Ratio dinámico
                Object.assign(elements.cropperSelection, {
                    aspectRatio: settings.aspectRatio,
                    initialCoverage: 0.5
                });
            }, 100);
        }
    });

    // Limpieza al cerrar
    elements.cropModalEl.addEventListener('hidden.bs.modal', function () {
        if (elements.cropperImage) {
            elements.cropperImage.removeAttribute('src');
        }
        elements.fileInput.value = '';
        tempImageSrc = null;
    });

    // Guardar (Crop & Save)
    if (elements.cropBtn) {
        elements.cropBtn.addEventListener('click', async function () {
            if (!elements.cropperSelection) return;

            const settings = getSettings();

            try {
                // Usar Width/Height dinámicos
                const canvas = await elements.cropperSelection.$toCanvas({
                    width: settings.width,
                    height: settings.height
                });

                const base64Data = canvas.toDataURL('image/jpeg', 0.9); // Calidad 90%

                // Actualizar inputs y vista previa
                elements.hiddenInput.value = base64Data;

                if (elements.previewImage) elements.previewImage.src = base64Data;
                if (elements.previewContainer) {
                    elements.previewContainer.classList.remove('d-none');
                    elements.previewContainer.style.setProperty('display', 'flex', 'important');
                }

                // Limpiar estados de error visuales
                elements.fileInput.classList.remove('is-invalid');
                elements.hiddenInput.classList.remove('is-invalid');

                const parentDiv = elements.fileInput.closest('.mb-3');
                if (parentDiv) {
                    const errorMsg = parentDiv.querySelector('.invalid-feedback');
                    if (errorMsg) errorMsg.textContent = '';
                }

                cropModal.hide();

            } catch (error) {
                console.error("Cropper error:", error);
            }
        });
    }
});