(function() {
    'use strict';

    angular
        .module('app')
        .controller('newFormula', newController);

    newController.$inject = ['materialService', 'formulaService', 'unitService', 'unitConvertService', '$route', 'validationService', '$window'];
    function newController($material, $formula, $unit, $convert, $route, $validation, $window) {
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
        vm.convertUnit = convertUnit;

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
                filterUnitDetails();
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

                for (let item of vm.selected) {
                    item.oldUnit = item.unit;
                }

                filterUnitDetails();
            }
        }

        function convertUnit(item) {
            const unit = item.unit;
            if (!unit || !item.oldUnit || unit === item.oldUnit) {
                return;
            }

            $convert.getFactor(item.oldUnit, unit)
                .then(function (r) {
                    if (!r.data) {
                        return;
                    }
                    console.log(r.data);

                    item.amount = item.amount * r.data.factor;
                    item.oldUnit = unit;
                });
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
            vm.formula.price = total;

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
                    oldUnit: material.unit,
                    amount: 1,
                    price: material.price,
                    total: material.price,
                    units: getUnitsByCode(vm.units, material.unit)
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

        function successAdded() {
            $window.location.href = $route.generate('formula_index');
        }

        function errorAdded(err) {
            $validation.handleFromHttpError(err);
        }

        function create() {
            const formula = vm.formula;
            formula.details = getDetails(vm.selected);

            $formula.add(formula)
                .then(successAdded, errorAdded);
        }

        function edit(id) {
            const formula = vm.formula;
            formula.details = getDetails(vm.selected);

            $formula.edit(id, formula)
                .then(successAdded, errorAdded);
        }

        function getDetails(items) {
            return items.map(function (item) {
                return {
                    material_id: item.material_id,
                    amount: item.amount,
                    unit: item.unit,
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

        function filterUnitDetails() {
            const materials = vm.selected;
            if (materials.length === 0 ||
                !vm.units) {
                return;
            }

            for (let material of materials) {
                material.units = getUnitsByCode(vm.units, material.unit);
            }
        }

        function getUnitsByCode(units, code) {
            const unit = units.find((item) => item.code === code);

            if (!unit) {
                return units;
            }

            const result = units.filter((item) => item.type === unit.type);

            return result.length === 0 ? units : result;
        }
    }
})();