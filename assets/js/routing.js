const routes = require('../../public/js/fos_js_routes.json');
import Routing from '../../public/bundles/fosjsrouting/js/router';
Routing.setRoutingData(routes);

window.Routing = Routing;