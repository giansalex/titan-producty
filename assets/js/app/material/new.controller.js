(function() {
    'use strict';

    angular
        .module('app')
        .controller('newMaterial', newController);

    newController.$inject = ['materialService', 'unitService', '$window'];
    function newController($material, $unit, $window) {
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
            $window.location.href = Routing.generate('material_index');
        }

        function errorAdded(err) {
            if (err.status !== 400) {
                return;
            }
            const data = err.data;
            if (!data || !data.errors) {
                return;
            }
            const errors = data.errors;
            for (let i = 0; i < errors.length; i++) {
                const element = errors[i];
                console.log(element.field + ':' + element.message);
            }
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