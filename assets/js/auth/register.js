(function () {
    let label;
    const element = $('#fos_user_registration_form_username');
    function createElement() {
        label = $('<label></label>');
        label.addClass('error');
        element.parent().parent().append(label);
    }

    createElement();
    element.change(function () {
        const value = $(this).val();
        if (!value) {
            return;
        }

        $.get('/api/user/exists/'+value).done(function (exists) {
            label.text(exists ? 'El usuario esta en uso' : '');
        });
    });
})();