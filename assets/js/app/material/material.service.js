'use strict';

class MaterialService {
    constructor($http, $route) {
        this.$http = $http;
        this.$route = $route;
    }

    get(id) {
        return this.$http.get(this.$route.generate('material_api_get', {id: id}));
    }

    add(material) {
        return this.$http.post(this.$route.generate('material_api_add'), material);
    }

    edit(id, material) {
        return this.$http.put(this.$route.generate('material_api_edit', {id: id}), material);
    }

    list() {
        return this.$http.get(this.$route.generate('material_api_list'));
    }

    copy(id) {
        return this.$http.post(this.$route.generate('material_api_duplicate', {id: id}));
    }

    static factory($http, $route) {
        return new MaterialService($http, $route);
    }
}

MaterialService.factory.$inject = ['$http', '$route'];

angular
    .module('app')
    .factory('materialService', MaterialService.factory);