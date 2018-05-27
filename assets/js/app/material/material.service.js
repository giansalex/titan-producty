(function() {
    'use strict';

    angular
        .module('app')
        .factory('materialService', materialService);

    materialService.$inject = ['$http'];
    function materialService($http) {
        return {
            add: add,
            get: get,
            edit: edit,
            list: list,
        };

        function get(id) {
            return $http.get(Routing.generate('material_api_get', {id: id}));
        }

        function add(material) {
            return $http.post(Routing.generate('material_api_add'), material);
        }

        function edit(id, material) {
            return $http.put(Routing.generate('material_api_edit', {id: id}), material);
        }

        function list() {
            return $http.get(Routing.generate('material_api_list'));
        }
    }
})();