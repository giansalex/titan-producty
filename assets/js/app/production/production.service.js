(function() {
    'use strict';

    angular
        .module('app')
        .factory('productionService', productionService);

    productionService.$inject = ['$http'];

    function productionService($http) {
        return {
            add: add,
            list: list,
        };

        function add(production) {
            return $http.post(Routing.generate('production_api_add'), production);
        }

        function list() {
            return $http.get(Routing.generate('production_api_list'));
        }
    }
})();