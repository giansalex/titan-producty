require('../../../node_modules/handsontable/dist/handsontable.css');
(async function() {
    'use strict';
    const Handsontable = require('handsontable');

    const mod = (function () {
        let hot;
        let codes;

        function getUnits() {
            return $.get(Routing.generate('unit_api_list'));
        }

        function initTable() {
            const container = document.getElementById('excel');
            hot = new Handsontable(container, {
                data : [],
                outsideClickDeselects: false,
                contextMenu: ['row_below', 'remove_row'],
                stretchH: "all",
                colHeaders: [
                    'Nombre',
                    'Código',
                    'Cantidad por Presentacion',
                    'Unidad',
                    'Costo por Presentación',
                    'Cantidad Inicial'],
                columns: [
                    { type : 'text'},
                    { type : 'text'},
                    { type : 'numeric'},
                    {
                        editor: 'select',
                        selectOptions: codes
                    },
                    { type : 'numeric'},
                    { type : 'numeric'},
                ],
                tableClassName: ['table'],
            });
        }

        async function init() {
            const data = await getUnits();
            codes = data.map((item) => item.code);

            initTable();
            changeRows(2);
        }

        function changeRows(cant) {
            hot.updateSettings({
                data : []
            });

            for (let i = 0; i < cant; i++){
                hot.alter('insert_row', 0);
            }
        }

        function validRow(row) {
            for (let i = 0; i < row.length; i++) {
                if (row[i]) {
                    return true;
                }
            }

            return false;
        }

        function getMaterials(data) {
            const materials = [];
            for (let i = 0; i < data.length; i++) {
                const row = data[i];
                if (!validRow(row)) {
                    continue;
                }

                const material = {
                    name: row[0],
                    code: row[1],
                    amount: Number(row[2]),
                    unit: row[3],
                    packing_price: Number(row[4]),
                    price: Number(row[5]),
                    stock: 0
                };
                materials.push(material);
            }

            return materials;
        }

        function validMaterials(materials) {
            const novalid = materials.find((item) => !codes.includes(item.unit));

            return !novalid;
        }

        function generateListHtmlError(errors) {
            const cList = $('<ul></ul>')
                            .addClass('text-danger');

            errors.forEach((error) => {
                $('<li/>')
                    .text(error.field + ' : ' + error.message)
                    .appendTo(cList);
            });

            return cList;
        }

        function showErrorValidations(errors) {
            const list = generateListHtmlError(errors);

            swal({
                icon: 'error',
                title: 'Error de Validación',
                content: list.get( 0 )
            });
        }

        function saveApi(data) {
            return $.ajax({
                url: Routing.generate('material_api_multiple'),
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(data),
            });
        }

        function save() {
            const data = hot.getData();
            const materials = getMaterials(data);

            if (!validMaterials(materials)) {
                swal('Atención', 'Algunas unidades ingresadas no cumplen con el formato', 'warning');
                return;
            }

            saveApi(materials)
                .then(processSuccess, processError);

            function processSuccess() {
                swal('Buen Trabajo!', 'La informacion ha sido guardada.', 'success');

                setTimeout(() => window.location.href = Routing.generate('material_index'), 500);
            }

            function processError(r) {
                if (r.status === 400 &&
                    r.responseJSON &&
                    r.responseJSON.errors) {

                    showErrorValidations(r.responseJSON.errors);
                    return;
                }

                swal('Error', 'No se pudo guardar la información!', 'error');
            }
        }

        return {
            init: init,
            changeRows: changeRows,
            save: save
        };
    })();
    
    $('#btnAply').click(function () {
        const value = Number($('#cantRep').val());
        if (value <= 0) {
            return;
        }

        mod.changeRows(value);
    });
    $('#btnSave').click(function () {
        mod.save();
    });
    await mod.init();

})();