(function() {
    'use strict';

    angular
        .module('app')
        .controller('newProduct', newController);

    newController.$inject = ['productService', 'formulaService', '$window'];
    function newController($product, $formula, $window) {
        const vm = this;
        vm.formulas = [];
        vm.selected = [];
        vm.product = {};
        vm.addMaterial = addMaterial;
        vm.delMaterial = delMaterial;
        vm.changeFormula = changeFormula;
        vm.get = get;
        vm.create = create;
        vm.edit = edit;

        activate();

        function activate() {
            $formula.list()
                .then(getFormulas);

            function getFormulas(res) {
                vm.formulas = res.data;
                console.log(vm.formulas);
            }
        }

        function get(id) {
            $product.get(id)
                .then(function (r) {
                    vm.product = r.data;
                    console.log(vm.product);
                });
            $product.materials(id)
                .then(getMaterials);

            function getMaterials(res) {
                vm.selected = res.data;
                console.log(vm.selected);
            }
        }

        function addMaterial(material) {
            $('#materialModal').modal('hide');
            const detail = {
                material_id: material.id,
                name: material.name,
                unit: material.unit,
                amount: 1,
                price: material.price,
                total: material.price
            };

            vm.selected.push(detail);
        }

        function delMaterial(index) {
            vm.selected.splice(index, 1);
        }

        function create() {
            const product = vm.product;
            product.details = getDetails(vm.selected);

            $product.add(product)
                .then(successAdded);

            function successAdded() {
                $window.location.href = Routing.generate('product_index');
            }
        }

        function edit(id) {
            const product = vm.product;
            product.details = getDetails(vm.selected);

            $product.edit(id, product)
                .then(successAdded);

            function successAdded() {
                $window.location.href = Routing.generate('product_index');
            }
        }
        
        function changeFormula() {
            const id = vm.product.formula_id;
            if (!id) {
                return;
            }

            $formula.materials(id)
                .then(getMaterials);

            function getMaterials(res) {
                vm.selected = res.data;
                console.log(vm.selected);
            }
        }

        function getDetails(items) {
            return items.map(function (item) {
                return {
                    material_id: item.material_id,
                    amount: item.amount,
                    price: item.price,
                    total: item.price * item.amount,
                };
            })
        }
    }
})();