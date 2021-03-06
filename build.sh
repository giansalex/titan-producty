npm i
composer install --no-dev -o
composer dump-autoload --optimize --no-dev --classmap-authoritative
composer run routing
npm run build
node docker/ci/s3-upload.js --action=add --dir=public/build --prefix=minventario/ --skip=manifest.json
# php ci/publish.php #with manifest.json
node docker/ci/s3-upload.js --action=del --dir=public/build --prefix=minventario/ --skip=manifest.json