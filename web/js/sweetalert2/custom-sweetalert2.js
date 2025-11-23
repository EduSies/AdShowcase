const swalNotify = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    showCloseButton: true,
    timer: 3000,
    showClass: {
        backdrop: 'swal2-noanimation', // disable backdrop animation
        popup: '', // disable popup animation
        icon: '' // disable icon animation
    },
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
    }
});

function swalSuccess(msg, iconHtml = '') {
    let conf = {
        theme: 'bootstrap-5-light',
        icon: 'success',
        html: '<span>'+msg+'</span>',
        customClass: { icon: 'swal2-no-border', container: 'swal2-fixed-width' }
    };
    if (iconHtml) {
        conf.iconHtml = iconHtml;
    }
    swalNotify.fire(conf);
}

function swalWarning(msg, iconHtml = '') {
    let conf = {
        theme: 'bootstrap-5-light',
        icon: 'warning',
        html: '<span>'+msg+'</span>',
        timer: 5000,
        customClass: { icon: 'swal2-no-border', container: 'swal2-fixed-width' }
    };
    if (iconHtml) {
        conf.iconHtml = iconHtml;
    }
    swalNotify.fire(conf);
}

function swalDanger(msg, iconHtml = '') {
    let conf = {
        theme: 'bootstrap-5-light',
        icon: 'error',
        timer: 5000,
        html: '<span>'+msg+'</span>',
        customClass: { icon: 'swal2-no-border', container: 'swal2-fixed-width' }
    };
    if (iconHtml) {
        conf.iconHtml = iconHtml;
    }
    swalNotify.fire(conf);
}

const swalDefault = Swal.mixin({
    title: 'Are you sure you want to perform this action?',
    icon: 'warning',
    html: 'Once deleted, you will not be able to recover it!',
    showCloseButton: false,
    showCancelButton: true,
    focusConfirm: false,
    confirmButtonText: 'Close',
    cancelButtonText: 'Cancel',
    reverseButtons: false,
    customClass: {
        confirmButton: 'btn btn-primary me-2',
        cancelButton: 'btn btn-secondary ms-2',
    },
    buttonsStyling: false
});

function swalFire(options, callback = null) {
    if ('title' in options) {
        options.title = '<h3>'+options.title+'</h3>';
    }
    if ('html' in options) {
        options.html = '<div>'+options.html+'</div>';
    }
    if (!('customClass' in options)) {
        options.customClass = {};
    }
    if (options.hasOwnProperty('showIcon') && options.showIcon === false) {
        options.customClass.icon = 'd-none';
    }
    if (!('confirmButton' in options.customClass)) {
        options.customClass.confirmButton = 'btn btn-primary me-2';
    }
    if (!('cancelButton' in options.customClass)) {
        options.customClass.cancelButton = 'btn btn-secondary ms-2';
    }

    if (callback) {
        return swalDefault.fire(options).then((dialog) => {
            if (dialog.isConfirmed) {
                callback();
            }
        });
    }

    return swalDefault.fire(options);
}