(function() {
    'use strict';

    angular
        .module('app')
        .controller('indexMaterial', indexController);

    indexController.$inject = ['materialService'];
    function indexController($material) {
        const vm = this;
        vm.getShowUrl = getShowUrl;
        vm.getEditUrl = getEditUrl;

        activate();

        function activate() {
            list();
        }

        function list() {
            $material.list()
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
    }
})();