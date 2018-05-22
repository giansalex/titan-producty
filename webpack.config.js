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
        'angular',
        './assets/js/admbs',
        './assets/js/routing',
        './assets/js/app/app.module',
        'sweetalert',
    ])
    .addEntry('material-new', [
        './assets/js/app/material/material.service',
        './assets/js/app/material/new.controller',
    ])
    .addEntry('formula-new', [
        './assets/js/app/material/material.service',
        './assets/js/app/formula/formula.service',
        './assets/js/app/formula/new.controller',
    ])
    .addEntry('product-new', [
        './assets/js/app/formula/formula.service',
        './assets/js/app/product/product.service',
        './assets/js/app/product/new.controller',
    ])
    .addEntry('production-new', [
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

module.exports = Encore.getWebpackConfig();
