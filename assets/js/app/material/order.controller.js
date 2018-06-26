(function() {
    'use strict';

    angular
        .module('app')
        .controller('orderMaterial', orderController);

    orderController.$inject = ['materialService', 'materialInventoryService', '$window'];
    function orderController($material, $inventory, $window) {
        const vm = this;
        vm.selected = [];
        vm.save = save;

        activate();

        function activate() {
            $material.list()
            .then(listSuccess);

            function listSuccess(r) {
                const data = r.data;
                data.forEach(function (item) {
                    item.change = 0;
                });
                vm.selected = data;
            }
        }

        function successSave() {
            $window.location.href = Routing.generate('material_index');
        }

        function save() {
            const list = getList();
            if (list.length === 0) {
                successSave();
                return;
            }

            $inventory.update(list)
                .then(successSave);
        }

        function getList() {
            const list = vm.selected;
            const result = [];

            list.forEach(function (element) {
                if (!element.change) {
                    return;
                }

                result.push({
                    id   : element.id,
                    value: element.stock + (element.change || 0)
                });
            });

            return result;
        }
    }
})();