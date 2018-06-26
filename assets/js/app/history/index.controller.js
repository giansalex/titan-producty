(function() {
    'use strict';

    angular
        .module('app')
        .controller('indexHistory', indexController);

    indexController.$inject = ['historyService'];
    function indexController($service) {
        const vm = this;
        vm.list = list;

        activate();

        function activate() {
        }

        function list(type) {
            $service.list(type)
                .then(function (r) {
                    vm.histories = r.data;
                });
        }
    }
})();