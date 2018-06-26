(function() {
    'use strict';

    angular
        .module('app')
        .factory('historyService', historyService);

    historyService.$inject = ['$http'];
    function historyService($http) {
        return {
            list: list,
        };

        function list(type) {
            return $http.get(Routing.generate('history_api_list', {type: type}));
        }
    }
})();