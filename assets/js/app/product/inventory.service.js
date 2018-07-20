(function() {
    'use strict';

    angular
        .module('app')
        .factory('productInventoryService', productInventoryService);

    productInventoryService.$inject = ['$http', '$route'];
    function productInventoryService($http, $route) {
        return {
            update: update
        };

        function update(list) {
            return $http.put($route.generate('product_api_inventory'), list);
        }
    }
})();