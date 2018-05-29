(function() {
    'use strict';

    angular
        .module('app')
        .factory('productionService', productionService);

    productionService.$inject = ['$http'];

    function productionService($http) {
        return {
            add: add,
            get: get,
            edit: edit,
            list: list,
        };

        function add(production) {
            return $http.post(Routing.generate('production_api_add'), production);
        }

        function get(id) {
            return $http.get(Routing.generate('production_api_get', {id: id}));
        }

        function edit(id, production) {
            return $http.put(Routing.generate('production_api_edit', {id: id}), production);
        }

        function list() {
            return $http.get(Routing.generate('production_api_list'));
        }
    }
})();