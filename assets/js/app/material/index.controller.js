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
            $service.copy(element.id)
                .then(function (r) {
                    vm.materials.push(r.data);
                });
        }
    }
})();