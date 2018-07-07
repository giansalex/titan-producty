'use strict';

class ValidationService {

    _generateListHtmlError(errors) {
        const cList = $('<ul></ul>')
            .addClass('text-danger');

        errors.forEach((error) => {
            $('<li/>')
                .text(error.field + ' : ' + error.message)
                .appendTo(cList);
        });

        return cList;
    }

    /**
     * Show errors on Popup
     * @param errors
     * @param title
     */
    showErrors(errors, title = 'Error de Validación') {
        const list = this._generateListHtmlError(errors);

        swal({
            icon: 'error',
            title: title,
            content: list.get( 0 )
        });
    }

    /**
     * Handle from Http Error. (HTTP Code: 400)
     * @param err
     * @returns {boolean}
     */
    handleFromHttpError(err) {
        if (err.status !== 400) {
            return false;
        }
        const data = err.data;
        if (!data || !data.errors) {
            return false;
        }

        const errors = data.errors;
        this.showErrors(errors);

        return true;
    }

    static factory () {
        return new ValidationService();
    }
}

angular
    .module('app')
    .factory('validationService', ValidationService.factory);