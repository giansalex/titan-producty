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

        activate();

        function activate() {
        }

        function get(id) {
            $material.get(id)
                .then(function (r) {
                    vm.material = r.data;
                });
        }

        function create() {
            const material = vm.material;

            $material.add(material)
                .then(successAdded);

            function successAdded() {
                $window.location.href = Routing.generate('material_index');
            }
        }

        function edit(id) {
            const material = vm.material;
            console.log(material);

            $material.edit(id, material)
                .then(successAdded);

            function successAdded() {
                $window.location.href = Routing.generate('material_index');
            }
        }
    }
})();