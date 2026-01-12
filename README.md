
# Secuencia completa de instalacion recomendada
composer install  
cp .env.example .env  
php artisan key:generate  

# Configurar .env con tus credenciales
DB_CONNECTION=mysql  
DB_HOST=127.0.0.1  
DB_PORT=3306  
DB_DATABASE=nombre_tu_base_datos  
DB_USERNAME=tu_usuario  
DB_PASSWORD=tu_password  

# Ejecutar las migraciones y seeders
php artisan migrate:fresh --seed

# Ejecutar el proyecto
php artisan serve
