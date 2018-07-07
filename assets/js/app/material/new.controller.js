(function() {
    'use strict';

    angular
        .module('app')
        .controller('newMaterial', newController);

    newController.$inject = ['materialService', 'unitService', '$route', 'validationService' ,'$window'];
    function newController($material, $unit, $route, $validation, $window) {
        const vm = this;
        vm.material = {};
        vm.get = get;
        vm.create = create;
        vm.edit = edit;
        vm.getPrice = getPrice;

        activate();

        function activate() {
            $unit.list()
                .then(function (r) {
                    vm.units = r.data;
                    filterUnits();
                });
        }

        function get(id) {
            $material.get(id)
                .then(function (r) {
                    vm.material = r.data;
                    filterUnits();
                });
        }

        function getPrice() {
            if (!vm.material.amount) {
                return 0;
            }
            const total = vm.material.packing_price || 0;

            vm.material.price = total / vm.material.amount;

            return vm.material.price;
        }

        function successAdded() {
            $window.location.href = $route.generate('material_index');
        }

        function errorAdded(err) {
            $validation.handleFromHttpError(err);
        }

        function create() {
            const material = vm.material;

            $material.add(material)
                .then(successAdded, errorAdded);
        }

        function edit(id) {
            const material = vm.material;
            console.log(material);

            $material.edit(id, material)
                .then(successAdded, errorAdded);
        }

        function filterUnits() {
            const material = vm.material;
            if (!material.id ||
                !vm.units) {
                return;
            }

            vm.units = getUnitsByCode(vm.units, material.unit);
        }

        function getUnitsByCode(units, code) {
            const unit = units.find((item) => item.code === code);

            if (!unit) {
                return units;
            }

            return units.filter((item) => item.type === unit.type);
        }
    }
})();