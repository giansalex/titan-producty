(function() {
    'use strict';

    angular
        .module('app')
        .controller('inventoryMaterial', inventoryController);

    inventoryController.$inject = ['materialService', 'materialInventoryService', '$window'];
    function inventoryController($material, $inventory, $window) {
        const vm = this;
        vm.selected = [];
        vm.save = save; 

        activate();

        function activate() {
            $material.list()
            .then(listSuccess);

            function listSuccess(r) {
                var data = r.data;
                data.forEach(function (item) {
                    item.change = item.stock;
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
            var result = [];

            list.forEach(function (element) {
                if (element.stock === element.change) {
                    return;
                }

                result.push({
                    id   : element.id,
                    value: element.change || 0
                });
            });

            return result;
        }
    }
})();