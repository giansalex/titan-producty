npm i
composer install --no-dev -o
composer dump-autoload --optimize --no-dev --classmap-authoritative
composer run routing
npm run build
# node ci/s3-upload.js #not manifest.json
# php ci/publish.php #with manifest.json