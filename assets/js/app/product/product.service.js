(function() {
    'use strict';

    angular
        .module('app')
        .factory('productService', productService);

    productService.$inject = ['$http', '$route'];

    function productService($http, $route) {
        return {
            add: add,
            get: get,
            edit: edit,
            list: list,
            materials: materials,
            copy: copy,
        };

        function add(product) {
            return $http.post($route.generate('product_api_add'), product);
        }

        function get(id) {
            return $http.get($route.generate('product_api_get', {id: id}));
        }

        function edit(id, product) {
            return $http.put($route.generate('product_api_edit', {id: id}), product);
        }

        function list() {
            return $http.get($route.generate('product_api_list'));
        }

        function materials(id) {
            return $http.get($route.generate('product_api_material', {id: id}));
        }

        function copy(id) {
            return $http.post($route.generate('product_api_duplicate', {id: id}));
        }
    }
})();