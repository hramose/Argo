composer dump-autoload
php artisan module:publish-migration Documents
php artisan migrate:refresh --seed
php artisan module:seed Documents
