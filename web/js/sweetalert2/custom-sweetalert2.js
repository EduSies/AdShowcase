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
        html: '<span class="common-label">'+msg+'</span>',
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
        html: '<span class="common-label">'+msg+'</span>',
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
        html: '<span class="common-label">'+msg+'</span>',
        customClass: { icon: 'swal2-no-border', container: 'swal2-fixed-width' }
    };
    if (iconHtml) {
        conf.iconHtml = iconHtml;
    }
    swalNotify.fire(conf);
}