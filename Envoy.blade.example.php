@servers(['web'=>['root@0.0.0.0']])

@task('dev', ['on'=>'web'])
    cd /var/www/docker-beikeshop/www/beikeshop && pwd && git pull
    cd /var/www/docker-beikeshop/docker && pwd && docker-compose ps
    docker exec beikeshop_com-php8_workspace_1 sh -c "cd beikeshop && composer install"
    docker exec beikeshop_com-php8_workspace_1 sh -c "cd beikeshop && php artisan migrate"
    docker exec beikeshop_com-php8_workspace_1 sh -c "cd beikeshop && npm install && npm run dev"
@endtask

@task('prod', ['on'=>'web'])
    cd /var/www/docker-beikeshop/www/beikeshop && pwd && git pull
    cd /var/www/docker-beikeshop/docker && pwd && docker-compose ps
    docker exec beikeshop_com-php8_workspace_1 sh -c "cd beikeshop && composer install"
    docker exec beikeshop_com-php8_workspace_1 sh -c "cd beikeshop && php artisan migrate"
    docker exec beikeshop_com-php8_workspace_1 sh -c "cd beikeshop && npm install && npm run prod"
@endtask
