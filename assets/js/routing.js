const routes = require('../../public/js/fos_js_routes.json');
const Routing = require('../../public/bundles/fosjsrouting/js/router').Routing;
Routing.setRoutingData(routes);

window.Routing = Routing;