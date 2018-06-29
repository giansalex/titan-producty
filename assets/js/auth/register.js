(function() {
    'use strict';

    function validateEmail(email) {
        const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }

    let label;
    const element = $('#fos_user_registration_form_email');
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

        if (!validateEmail(value)) {
            label.text('El email no es válido');
            return;
        }

        $.get('/api/user/exists/'+value).done(function (exists) {
            label.text(exists ? 'El email ya esta en uso' : '');
        });
    });
})();