(function() {
    'use strict';

    angular
        .module('app')
        .factory('unitService', unitService);

    unitService.$inject = ['$http', '$route'];

    function unitService($http, $route) {
        return {
            list: list,
        };

        function list() {
            return $http.get($route.generate('unit_api_list'));
        }
    }
})();