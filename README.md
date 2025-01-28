composer create-project symfony/skeleton:"6.4.*" aksamV2
composer install 
composer require webapp 
php -S localhost:8000 -t public
https://symfony.com/doc/current/the-fast-track/en/3-zero.html#initializing-the-project
 
## Configuration webpack:
rm -rf public/build
yarn encore dev
npm run build
yarn remove bootstrap
yarn add bootstrap
tree
rm -rf node_modules yarn.lock
yarn install
yarn add file-loader@^6.0.0 --dev
rm -rf node_modules/.cache
 
nano assets/bootstrap.js
yarn add @symfony/stimulus-bundle --dev
yarn add stimulus --dev
composer require symfony/stimulus-bundle
nano assets/bootstrap.js
yarn --version
yarn add @symfony/webpack-encore --dev