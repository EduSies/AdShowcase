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

        // Elementos Cropper
        cropperImage: document.querySelector('cropper-image'),
        cropperSelection: document.querySelector('cropper-selection'),

        // Referencias para redimensionar
        modalDialog: document.querySelector('#crop-modal .modal-dialog'),
        modalBody: document.querySelector('#crop-modal .modal-body')
    };

    if (!elements.fileInput || !elements.cropModalEl || !elements.cropperImage) return;

    const bs = window.bootstrap || bootstrap;
    const cropModal = new bs.Modal(elements.cropModalEl);

    let tempImageSrc = '';

    // 1. Selección de archivo
    elements.fileInput.addEventListener('change', function (e) {
        const files = e.target.files;
        if (files && files.length > 0) {
            const file = files[0];

            if (!file.type.startsWith('image/')) {
                alert('Please select a valid image file.');
                elements.fileInput.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function (evt) {
                // ★ CLAVE: No asignamos el src todavía. Lo guardamos.
                tempImageSrc = evt.target.result;

                // Abrimos el modal primero. La imagen se cargará cuando termine la animación.
                cropModal.show();
            };
            reader.readAsDataURL(file);
        }
    });

    // 2. Evento: Modal Visible
    elements.cropModalEl.addEventListener('shown.bs.modal', function () {
        if (!tempImageSrc) return;

        elements.cropperImage.src = tempImageSrc;

        if (elements.cropperSelection) {
            elements.cropperSelection.$center();
            Object.assign(elements.cropperSelection, {
                aspectRatio: 35 / 9,
                initialCoverage: 0.8
            });
        }
    });

    // 3. Limpieza
    elements.cropModalEl.addEventListener('hidden.bs.modal', function () {
        elements.cropperImage.src = '';
        elements.fileInput.value = '';
        tempImageSrc = '';
    });

    // 4. Guardar (Sin cambios)
    elements.cropBtn.addEventListener('click', async function () {
        if (!elements.cropperSelection) return;
        try {
            const canvas = await elements.cropperSelection.$toCanvas({ width: 1400, height: 360 });
            const base64Data = canvas.toDataURL('image/jpeg', 0.9);
            elements.hiddenInput.value = base64Data;
            elements.previewImage.src = base64Data;
            if (elements.previewContainer) {
                elements.previewContainer.classList.remove('d-none');
                elements.previewContainer.style.setProperty('display', 'flex', 'important');
            }
            cropModal.hide();
        } catch (error) { console.error(error); }
    });
});