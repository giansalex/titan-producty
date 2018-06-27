(function() {
    'use strict';

    angular
        .module('app')
        .controller('indexProduct', indexController);

    indexController.$inject = ['productService'];
    function indexController($service) {
        const vm = this;
        vm.copy = copy;
        vm.getShowUrl = getShowUrl;
        vm.getEditUrl = getEditUrl;

        activate();

        function activate() {
            $service.list()
                .then(getElements);

            function getElements(res) {
                vm.products = res.data;
            }
        }

        function getShowUrl(id) {
            return Routing.generate('product_show', {id: id});
        }

        function getEditUrl(id) {
            return Routing.generate('product_edit', {id: id});
        }

        function copy(element) {
            $service.copy(element.id)
                .then(function (r) {
                    vm.products.push(r.data);
                });
        }
    }
})();