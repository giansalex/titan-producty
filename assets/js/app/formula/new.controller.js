(function() {
    'use strict';

    angular
        .module('app')
        .controller('newFormula', newController);

    newController.$inject = ['materialService', 'formulaService', '$window'];
    function newController($material, $formula, $window) {
        const vm = this;
        vm.materials = [];
        vm.selected = [];
        vm.formula = {};
        vm.addMaterial = addMaterial;
        vm.get = get;
        vm.edit = edit;
        vm.delMaterial = delMaterial;
        vm.create = create;

        activate();

        function activate() {
            $material.list()
                .then(getMaterials);

            function getMaterials(res) {
                vm.materials = res.data;
                console.log(vm.materials);
            }
        }

        function get(id) {
            $formula.get(id)
                .then(function (r) {
                    vm.formula = r.data;
                    console.log(vm.formula);
                });
            $formula.materials(id)
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
            const formula = vm.formula;
            formula.details = getDetails(vm.selected);

            $formula.add(formula)
                .then(successAdded);

            function successAdded() {
                $window.location.href = Routing.generate('formula_index');
            }
        }

        function edit(id) {
            const formula = vm.formula;
            formula.details = getDetails(vm.selected);

            $formula.edit(id, formula)
                .then(successAdded);

            function successAdded() {
                $window.location.href = Routing.generate('formula_index');
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