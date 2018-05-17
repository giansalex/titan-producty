(function() {
    'use strict';

    angular
        .module('app')
        .factory('materialService', materialService);

    materialService.$inject = ['$http'];
    function materialService($http) {
        return {
            list: list,
        };

        function list() {
            return $http.get(Routing.generate('material_api_list'));
        }
    }
})();