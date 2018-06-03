(function() {
    'use strict';

    angular
        .module('app')
        .controller('newMaterial', newController);

    newController.$inject = ['materialService', '$window'];
    function newController($material, $window) {
        const vm = this;
        vm.material = {};
        vm.get = get;
        vm.create = create;
        vm.edit = edit;
        vm.getPrice = getPrice;

        activate();

        function activate() {
        }

        function get(id) {
            $material.get(id)
                .then(function (r) {
                    vm.material = r.data;
                });
        }

        function getPrice() {
            if (!vm.material.amount) {
                return 0;
            }
            var total = vm.material.packing_price || 0;

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
            var data = err.data;
            if (!data || !data.errors) {
                return;
            }
            var errors = data.errors;
            for (var i = 0; i < errors.length; i++) {
                var element = errors[i];
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
    }
})();