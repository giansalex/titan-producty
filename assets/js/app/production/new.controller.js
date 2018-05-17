(function() {
    'use strict';

    angular
        .module('app')
        .controller('newproduction', newController);

    newController.$inject = ['productionService', 'productService', '$window'];
    function newController($production, $product, $window) {
        const vm = this;
        vm.products = [];
        vm.selected = [];
        vm.production = {};
        vm.changeproduct = changeproduct;
        vm.create = create;

        activate();

        function activate() {
            $product.list()
                .then(getProducts);

            function getProducts(res) {
                vm.products = res.data;
                console.log(vm.products);
            }
        }

        function create() {
            const production = vm.production;

            $production.add(production)
                .then(successAdded);

            function successAdded() {
                $window.location.href = Routing.generate('production_index');
            }
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