(function() {
    'use strict';

    angular
        .module('app')
        .factory('materialInventoryService', materialInventoryService);

    materialInventoryService.$inject = ['$http', '$route'];
    function materialInventoryService($http, $route) {
        return {
            update: update
        };

        function update(list) {
            return $http.put($route.generate('material_api_inventory'), list);
        }
    }
})();