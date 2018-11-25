(function() {
    'use strict';

    angular
        .module('app')
        .controller('indexProduction', indexController);

    indexController.$inject = ['productionService', '$route'];
    function indexController($service, $route) {
        const vm = this;
        vm.copy = copy;
        vm.getShowUrl = getShowUrl;
        vm.getEditUrl = getEditUrl;

        activate();

        function activate() {
            $service.list()
                .then(getElements);

            function getElements(res) {
                vm.productions = res.data;
            }
        }

        function getShowUrl(id) {
            return $route.generate('production_show', {id: id});
        }

        function getEditUrl(id) {
            return $route.generate('production_edit', {id: id});
        }

        function copy(element) {
            $service.copy(element.id)
                .then(function (r) {
                    vm.productions.push(r.data);
                });
        }
    }
})();