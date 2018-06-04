(function() {
    'use strict';

    angular
        .module('app')
        .factory('materialInventoryService', materialInventoryService);

    materialInventoryService.$inject = ['$http'];
    function materialInventoryService($http) {
        return {
            update: update
        };

        function update(list) {
            return $http.put(Routing.generate('material_api_inventory'), list);
        }
    }
})();