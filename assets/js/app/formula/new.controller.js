(function() {
    'use strict';

    angular
        .module('app')
        .controller('newFormula', newController);

    newController.$inject = ['materialService', 'formulaService', 'unitService', '$window'];
    function newController($material, $formula, $unit, $window) {
        const vm = this;
        vm.materials = [];
        vm.selected = [];
        vm.formula = {};
        vm.addMaterial = addMaterial;
        vm.get = get;
        vm.edit = edit;
        vm.delMaterial = delMaterial;
        vm.create = create;
        vm.getCosto = getCosto;
        vm.getTotal = getTotal;

        activate();

        function activate() {
            $material.list()
                .then(getMaterials);

            $unit.list()
                .then(getUnits);

            function getMaterials(res) {
                vm.materials = res.data;
            }

            function getUnits(r) {
                vm.units = r.data;
                filterUnits();
            }
        }

        function get(id) {
            $formula.get(id)
                .then(getFormula);
            $formula.materials(id)
                .then(getMaterials);

            function getFormula(r) {
                vm.formula = r.data;
                filterUnits();
            }

            function getMaterials(res) {
                vm.selected = res.data;
            }
        }

        function getCosto(detail) {
            const price = detail.price || 0;
            const amount = detail.amount || 0;

            detail.total = price * amount;

            return detail.total;
        }

        function getTotal() {
            let total = 0;
            vm.selected.forEach(function (element) {
                total += element.total;
            });

            return total;
        }

        function addMaterial() {
            $('#materialModal').modal('hide');
            const materials = getMaterials();

            for (let material of materials) {
                const detail = {
                    material_id: material.id,
                    name: material.name,
                    unit: material.unit,
                    amount: 1,
                    price: material.price,
                    total: material.price
                };

                vm.selected.push(detail);
                material.checked = false;
            }
        }

        function* getMaterials() {
            for (let material of vm.materials) {
                if (material.checked) {
                    yield material;
                }
            }
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
                    total: item.total,
                };
            })
        }

        function filterUnits() {
            const formula = vm.formula;
            if (!formula.id ||
                !vm.units) {
                return;
            }

            vm.units = getUnitsByCode(vm.units, formula.unit);
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