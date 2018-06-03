(function() {
    'use strict';

    angular
        .module('app')
        .controller('newProduction', newController);

    newController.$inject = ['productionService', 'productService', '$window'];
    function newController($production, $product, $window) {
        const vm = this;
        vm.products = [];
        vm.selected = [];
        vm.production = {};
        vm.changeproduct = changeproduct;
        vm.get = get;
        vm.create = create;
        vm.edit = edit;
        vm.getTotal = getTotal;

        activate();

        function activate() {
            $product.list()
                .then(getProducts);

            function getProducts(res) {
                vm.products = res.data;
                console.log(vm.products);
            }
        }

        function get(id) {
            $production.get(id)
                .then(getProduction);

            function getProduction(res) {
                vm.production = res.data;
                console.log(vm.production);
                vm.changeproduct();
            }
        }

        function getTotal() {
            var total = 0;
            vm.selected.forEach(function (element) {
                total += element.total;
            });

            return total;
        }

        function successAdded() {
            $window.location.href = Routing.generate('production_index');
        }

        function create() {
            const production = vm.production;

            $production.add(production)
                .then(successAdded);
        }

        function edit(id) {
            const production = vm.production;

            $production.edit(id, production)
                .then(successAdded);
        }

        function changeproduct() {
            const id = vm.production.product_id;
            if (!id) {
                return;
            }

            $product.materials(id)
                .then(getMaterials);

            function getMaterials(res) {
                vm.selected = res.data;
                console.log(vm.selected);
            }
        }
    }
})();