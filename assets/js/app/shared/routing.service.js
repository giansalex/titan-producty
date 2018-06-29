'use strict';

class RoutingService {

    generate(name, opt_params, absolute) {
        return Routing.generate(name, opt_params, absolute);
    }

    static factory () {
        return new RoutingService();
    }
}

angular
    .module('app')
    .factory('$route', RoutingService.factory);