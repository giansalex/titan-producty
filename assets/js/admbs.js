require('../../node_modules/adminbsb-materialdesign/css/themes/theme-red.css');
require('../../node_modules/adminbsb-materialdesign/plugins/animate-css/animate.css');

angular.module('main', []);

const routes = require('../../public/js/fos_js_routes.json');
import Routing from '../../public/bundles/fosjsrouting/js/router';
Routing.setRoutingData(routes);

window.Routing = Routing;