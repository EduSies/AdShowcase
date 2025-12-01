(function(){
    $(document).on('click','a.js-delete', function(e){
        e.preventDefault();
        e.stopPropagation();

        let button = $(this);
        let url = button.data("href");

        swalFire({
            title: deleteConfirmJs,
            confirmButtonText: "Continue",
            cancelButtonText: "Cancel",
            customClass: {container: 'swal2-cancel-pr-container'}
        }).then((dialog) => {
            if (dialog.isConfirmed) {
                $.ajax({
                    method: 'post',
                    url: url,
                    data: {}
                }).done(function (response) {
                    if (response.success === true) {
                        swalSuccess(response.message);

                        let dt = $('#'+idDataTable).DataTable();
                        let settings = dt.settings()[0] || {};
                        let hasAjax = !!(settings.oInit && settings.oInit.ajax) || !!settings.ajax || !!settings.sAjaxSource;

                        if (hasAjax) {
                            dt.ajax.reload(null, false);
                        } else {
                            var tr = button.closest('tr');
                            dt.row(tr).remove().draw(false);
                        }
                    } else {
                        swalDanger(response.message);
                    }
                });
            }
        })
    });
})();