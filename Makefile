deploy:
	php artisan optimize; \
    php artisan migrate; \
    php artisan optimize; \
    php artisan db:seed; \
    php artisan l5-swagger:generate; \
