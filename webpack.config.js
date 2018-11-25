const Encore = require('@symfony/webpack-encore');

Encore
    // the project directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // the public path used by the web server to access the previous directory
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    .createSharedEntry('vendor', [
        'jquery',
        'bootstrap',
        'node-waves',
        './assets/js/vendor'
    ])
    .addEntry('admin', [
        'babel-polyfill',
        'angular',
        './assets/js/admbs',
        './assets/js/routing',
        './assets/js/app/app.module',
        './assets/js/app/shared/routing.service',
        './assets/js/app/shared/validation.service',
        './assets/js/app/unit/unit.service',
        './assets/js/app/unit-convert/convert.service',
        './assets/js/app/material/material.service',
        'sweetalert',
    ])
    .addEntry('register', [
        './assets/js/auth/register',
    ])
    .addEntry('material/new', [
        './assets/js/app/material/new.controller',
    ])
    .addEntry('material/index', [
        './assets/js/app/material/index.controller',
    ])
    .addEntry('history/index', [
        './assets/js/app/history/history.service',
        './assets/js/app/history/index.controller',
    ])
    .addEntry('material/inventory', [
        './assets/js/app/material/inventory.service',
        './assets/js/app/material/inventory.controller',
    ])
    .addEntry('material/order', [
        './assets/js/app/material/inventory.service',
        './assets/js/app/material/order.controller',
    ])
    .addEntry('material/multiple', [
        './assets/js/material/multiple',
    ])
    .addEntry('formula/index', [
        './assets/js/app/formula/formula.service',
        './assets/js/app/formula/index.controller',
    ])
    .addEntry('formula/new', [
        './assets/js/app/formula/formula.service',
        './assets/js/app/formula/new.controller',
    ])
    .addEntry('product/index', [
        './assets/js/app/product/product.service',
        './assets/js/app/product/index.controller',
    ])
    .addEntry('product/new', [
        './assets/js/app/formula/formula.service',
        './assets/js/app/product/product.service',
        './assets/js/app/product/new.controller',
    ])
    .addEntry('product/sale', [
        './assets/js/app/product/inventory.service',
        './assets/js/app/product/sale.controller',
    ])
    .addEntry('product/inventory', [
        './assets/js/app/product/inventory.service',
        './assets/js/app/product/inventory.controller',
    ])
    .addEntry('production/index', [
        './assets/js/app/production/production.service',
        './assets/js/app/production/index.controller',
    ])
    .addEntry('production/new', [
        './assets/js/app/product/product.service',
        './assets/js/app/production/production.service',
        './assets/js/app/production/new.controller',
    ])
    // uncomment to create hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    // uncomment to define the assets of the project
    // .addEntry('js/app', './assets/js/app.js')
    // .addStyleEntry('css/app', './assets/css/app.scss')

    // uncomment if you use Sass/SCSS files
    // .enableSassLoader()

    // uncomment for legacy applications that require $/jQuery as a global variable
    .autoProvidejQuery()
;

if (Encore.isProduction()) {
    Encore.setPublicPath('https://d3i2t95ygjzen4.cloudfront.net');

    // guarantee that the keys in manifest.json are *still*
    // prefixed with build/
    // (e.g. "build/dashboard.js": "https://my-cool-app.com.global.prod.fastly.net/dashboard.js")
    Encore.setManifestKeyPrefix('build/');
}

module.exports = Encore.getWebpackConfig();
