(function() {
    'use strict';

    angular
        .module('app')
        .factory('formulaService', formulaService);

    formulaService.$inject = ['$http'];

    function formulaService($http) {
        return {
            add: add,
            list: list,
            materials: materials
        };

        function add(formula) {
            return $http.post(Routing.generate('formula_api_add'), formula);
        }

        function list() {
            return $http.get(Routing.generate('formula_api_list'));
        }

        function materials(id) {
            return $http.get(Routing.generate('formula_api_material', {id: id}));
        }
    }
})();