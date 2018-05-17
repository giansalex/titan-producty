(function() {
    'use strict';

    angular
        .module('app')
        .factory('productService', productService);

    productService.$inject = ['$http'];

    function productService($http) {
        return {
            add: add,
            list: list,
            materials: materials,
        };

        function add(product) {
            return $http.post(Routing.generate('product_api_add'), product);
        }

        function list() {
            return $http.get(Routing.generate('product_api_list'));
        }

        function materials(id) {
            return $http.get(Routing.generate('product_api_material', {id: id}));
        }
    }
})();