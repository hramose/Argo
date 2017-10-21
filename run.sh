composer dump-autoload
php artisan module:publish-migration Pawfinders
php artisan migrate:refresh --seed
php artisan module:seed Pawfinders
