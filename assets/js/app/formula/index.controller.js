(function() {
    'use strict';

    angular
        .module('app')
        .controller('indexFormula', indexController);

    indexController.$inject = ['formulaService', '$route'];
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
                vm.formulas = res.data;
                console.log(vm.formulas);
            }
        }

        function getShowUrl(id) {
            return $route.generate('formula_show', {id: id});
        }

        function getEditUrl(id) {
            return $route.generate('formula_edit', {id: id});
        }

        function copy(formula) {
            $service.copy(formula.id)
                .then(function (r) {
                    vm.formulas.push(r.data);
                });
        }
    }
})();