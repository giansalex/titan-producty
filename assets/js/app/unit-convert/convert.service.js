(function() {
    'use strict';

    angular
        .module('app')
        .factory('unitConvertService', unitConvertService);

    unitConvertService.$inject = ['$http'];

    function unitConvertService($http) {
        return {
            getFactor: getFactor,
        };

        function getFactor(from, to) {
            return $http.get(Routing.generate('unitconvert_api_factor', {source: from, target: to}));
        }
    }
})();