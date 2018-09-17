(function() {
    'use strict';

    angular
        .module('app')
        .controller('newProduct', newController);

    newController.$inject = ['productService', 'formulaService', 'materialService', 'unitService', 'unitConvertService', '$route', 'validationService', '$window'];
    function newController($product, $formula, $material, $unit, $convert, $route, $validation, $window) {
        const vm = this;
        vm.formulas = [];
        vm.selected = [];
        vm.formula = {};
        vm.product = {};
        vm.addMaterial = addMaterial;
        vm.delMaterial = delMaterial;
        vm.changeFormula = changeFormula;
        vm.get = get;
        vm.create = create;
        vm.edit = edit;
        vm.getCosto = getCosto;
        vm.getTotal = getTotal;
        vm.getCantUnidad = getCantUnidad;
        vm.convertUnit = convertUnit;

        activate();

        function activate() {
            $formula.list()
                .then(getFormulas);
            $material.list()
                .then(getMaterials);
            $unit.list()
                .then(getUnits);

            function getFormulas(res) {
                vm.formulas = res.data;
            }

            function getMaterials(res) {
                vm.materials = res.data;
            }

            function getUnits(r) {
                vm.units = r.data;
                filterUnitDetails();
            }
        }

        function get(id) {
            $product.get(id)
                .then(getProduct);
            $product.materials(id)
                .then(getMaterials);

            function getProduct(r) {
                vm.product = r.data;
            }

            function getMaterials(res) {
                vm.selected = res.data;
                filterUnitDetails();
            }
        }

        function convertUnit(item) {
            const newUnit = item.unit;
            if (!newUnit || !item.prevUnit || newUnit === item.prevUnit) {
                return;
            }

            $convert.getFactor(item.prevUnit, newUnit)
                .then(function (r) {
                    if (!r.data) {
                        return;
                    }
                    console.log(r.data);
                    const factor = r.data.factor;

                    item.amount = item.amount * factor;
                    item.prevUnit = newUnit;
                    item.factor = (item.factor || 1) * factor;
                });
        }

        function getCosto(detail) {
            const price = detail.price || 0;
            const amount = detail.amount || 0;
            const factor = detail.factor || 1;
            const origin = amount / factor;

            detail.total = price * origin;

            return detail.total;
        }

        function getTotal() {
            let total = 0;
            vm.selected.forEach(function (element) {
                total += element.total;
            });
            vm.product.price = total;

            return total;
        }

        function addMaterial(material) {
            $('#materialModal').modal('hide');
            const materials = getMaterials();
            for (let material of materials) {
                var unit = material.unit;
                const detail = {
                    material_id: material.id,
                    name: material.name,
                    unit: unit,
                    funit: unit,
                    prevUnit: unit,
                    factor: 1,
                    amount: 1,
                    price: material.price,
                    units: getUnitsByCode(vm.units, unit)
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
            $window.location.href = $route.generate('product_index');
        }

        function errorAdded(err) {
            $validation.handleFromHttpError(err);
        }

        function create() {
            const product = vm.product;
            product.details = getDetails(vm.selected);

            $product.add(product)
                .then(successAdded, errorAdded);
        }

        function edit(id) {
            const product = vm.product;
            product.details = getDetails(vm.selected);

            $product.edit(id, product)
                .then(successAdded, errorAdded);
        }
        
        function changeFormula() {
            const id = vm.product.formula_id;
            if (!id) {
                return;
            }

            $formula.get(id)
                .then(function (r) {
                    const formula = r.data;
                    vm.formula = formula;
                    vm.product.unit = formula.unit;
                    vm.product.base_amount = formula.amount;
                });
            $formula.materials(id)
                .then(getMaterials);

            function getMaterials(res) {
                const data = res.data;
                data.forEach((item) => {
                    item.funit = item.unit;
                    item.prevUnit = item.unit;
                    item.units = getUnitsByCode(vm.units, item.unit);
                });
                vm.selected = data;
                console.log(vm.selected);
            }
        }

        function getDetails(items) {
            return items.map(function (item) {
                return {
                    material_id: item.material_id,
                    amount: item.amount,
                    price: item.price,
                    unit: item.unit,
                    total: item.total,
                };
            })
        }

        function getCantUnidad(value) {
            if (!value) {
                return;
            }
            const amount = vm.product.amount || 1;
            const base = vm.product.base_amount || 1;

            const factor = amount / base;

            return value / factor;
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

            return units.filter((item) => item.type === unit.type);
        }
    }
})();