'use strict';

class MaterialIndexController {

    constructor($service, $route) {
        this.$service = $service;
        this.$route = $route;
        this.list();
    }

    list() {
        this.$service.list()
            .then((r) => this.materials = r.data);
    }

    getShowUrl(id) {
        return this.$route.generate('material_show', {id: id});
    }

    getEditUrl(id) {
        return this.$route.generate('material_edit', {id: id});
    }

    copy(element) {
        this.$service.copy(element.id)
            .then((r) => this.materials.push(r.data));
    }
}

MaterialIndexController.$inject = ['materialService', '$route'];

angular
    .module('app')
    .controller('indexMaterial', MaterialIndexController);