(function() {
    'use strict';

    angular
        .module('app')
        .factory('formulaService', formulaService);

    formulaService.$inject = ['$http', '$route'];

    function formulaService($http, $route) {
        return {
            add: add,
            get: get,
            list: list,
            edit: edit,
            materials: materials,
            copy: copy,
        };

        function add(formula) {
            return $http.post($route.generate('formula_api_add'), formula);
        }

        function get(id) {
            return $http.get($route.generate('formula_api_get', {id: id}));
        }

        function list() {
            return $http.get($route.generate('formula_api_list'));
        }

        function edit(id, formula) {
            return $http.put($route.generate('formula_api_edit', {id: id}), formula);
        }

        function materials(id) {
            return $http.get($route.generate('formula_api_material', {id: id}));
        }

        function copy(id) {
            return $http.post($route.generate('formula_api_duplicate', {id: id}));
        }
    }
})();