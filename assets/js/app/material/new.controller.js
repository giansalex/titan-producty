(function() {
    'use strict';

    angular
        .module('app')
        .controller('newMaterial', newController);

    newController.$inject = ['materialService', '$window'];
    function newController($material, $window) {
        const vm = this;
        vm.material = {};
        vm.create = create;

        activate();

        function activate() {
        }

        function create() {
            const material = vm.material;

            $material.add(material)
                .then(successAdded);

            function successAdded() {
                $window.location.href = Routing.generate('material_index');
            }
        }
    }
})();