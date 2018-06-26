(function() {
    'use strict';

    angular
        .module('app')
        .controller('indexMaterial', indexController);

    indexController.$inject = ['materialService'];
    function indexController($service) {
        const vm = this;
        vm.copy = copy;
        vm.getShowUrl = getShowUrl;
        vm.getEditUrl = getEditUrl;

        activate();

        function activate() {
            list();
        }

        function list() {
            $service.list()
                .then(function (r) {
                    vm.materials = r.data;
                });
        }

        function getShowUrl(id) {
            return Routing.generate('material_show', {id: id});
        }

        function getEditUrl(id) {
            return Routing.generate('material_edit', {id: id});
        }

        function copy(element) {
            const idx = vm.materials.indexOf(element);
            $service.copy(element.id)
                .then(function (r) {
                    vm.materials.splice(idx + 1, 0, r.data);
                });
        }
    }
})();