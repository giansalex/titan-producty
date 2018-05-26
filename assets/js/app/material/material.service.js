(function() {
    'use strict';

    angular
        .module('app')
        .factory('materialService', materialService);

    materialService.$inject = ['$http'];
    function materialService($http) {
        return {
            add: add,
            edit: edit,
            list: list,
        };

        function add(material) {
            return $http.post(Routing.generate('material_api_add'), material);
        }

        function edit(id, material) {
            return $http.put(Routing.generate('material_api_edit'), material);
        }

        function list() {
            return $http.get(Routing.generate('material_api_list'));
        }
    }
})();