(function() {
    'use strict';

    angular
        .module('app')
        .factory('productionService', productionService);

    productionService.$inject = ['$http', '$route'];

    function productionService($http, $route) {
        return {
            add: add,
            get: get,
            edit: edit,
            list: list,
            copy: copy,
        };

        function add(production) {
            return $http.post($route.generate('production_api_add'), production);
        }

        function get(id) {
            return $http.get($route.generate('production_api_get', {id: id}));
        }

        function edit(id, production) {
            return $http.put($route.generate('production_api_edit', {id: id}), production);
        }

        function list() {
            return $http.get($route.generate('production_api_list'));
        }

        function copy(id) {
            return $http.post($route.generate('production_api_duplicate', {id: id}));
        }
    }
})();