(function() {
    'use strict';

    angular
        .module('app')
        .controller('inventoryProduct', inventoryController);

    inventoryController.$inject = ['productService', 'productInventoryService', '$route', '$window'];
    function inventoryController($repository, $inventory, $route, $window) {
        const vm = this;
        vm.selected = [];
        vm.save = save; 

        activate();

        function activate() {
            $repository.list()
            .then(listSuccess);

            function listSuccess(r) {
                const data = r.data;
                data.forEach(function (item) {
                    item.change = item.stock;
                });
                vm.selected = data;
            }
        }

        function successSave() {
            $window.location.href = $route.generate('product_index');
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
            let result = [];

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