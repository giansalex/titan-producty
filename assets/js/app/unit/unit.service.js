(function() {
    'use strict';

    angular
        .module('app')
        .factory('unitService', unitService);

    unitService.$inject = ['$http'];

    function unitService($http) {
        return {
            list: list,
        };

        function list() {
            return $http.get(Routing.generate('unit_api_list'));
        }
    }
})();