(function() {
    'use strict';

    angular
        .module('app')
        .factory('formulaService', formulaService);

    formulaService.$inject = ['$http'];

    function formulaService($http) {
        return {
            add: add,
        };

        function add(formula) {
            return $http.post(Routing.generate('formula_api_add'), formula);
        }
    }
})();