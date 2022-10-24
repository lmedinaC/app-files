# Modulo Files.


## REQUIREMENTS

* Composer
* Laravel
* XAMPP 

## Installation
    Replace $USER = your User db and $PASSWORD = your password db
~~~
    mysqladmin -u$USER -p$PASSWORD create files_db
    git clone https://github.com/lmedinaC/app-files.git
    cd app-files
    php -r "file_exists('.env') || copy('.env.example', '.env');"
    composer install
    php artisan key:generate
    php artisan storage:link
    php artisan migrate
    php artisan db:seed
~~~



## RUN PROYECT 

~~~
:php artisan serve 
~~~

Autor: @lmedinaC