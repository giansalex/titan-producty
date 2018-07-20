(function() {
    'use strict';

    angular
        .module('app')
        .controller('saleProduct', saleController);

    saleController.$inject = ['productService', 'productInventoryService', '$route', '$window'];
    function saleController($repository, $inventory, $route, $window) {
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
                    item.change = 0;
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
            const result = [];

            list.forEach(function (element) {
                if (!element.change) {
                    return;
                }

                result.push({
                    id   : element.id,
                    value: element.stock - (element.change || 0)
                });
            });

            return result;
        }
    }
})();