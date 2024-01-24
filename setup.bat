@echo off

echo Avvio dei container Docker...
docker-compose up -d

docker-compose exec app composer config --global process-timeout 2000

echo Esecuzione di Composer Install...
docker-compose exec app composer install

echo Esecuzione di NPM Install...
docker-compose exec app npm install

echo Esecuzione di NPM Run Build...
docker-compose exec app npm run build

echo Esecuzione di PHP Artisan Migrate...
docker-compose exec app php artisan migrate --force

echo Setup completato!
pause
