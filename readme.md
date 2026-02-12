# Tiny vue project by [atk]
This project is the general purpose tiny erp system powered by [Laravel](http://laravel.com).


# Tech Stacks
 - Laravel 5.7
 - MySQL 5.7
 - PHP 7.1.3^

# Installation for local network
 - Install the latest stable [XAMPP](https://www.apachefriends.org/index.html)
 - Install latest stable [Composer](https://getcomposer.org/)
 - Clone the project from the Git repo to the `xampp/htdocs` (C:/xampp/htdocs for windows)
 - Go to the `xampp/htdocs/{yourproject}` and open the terminal there
 - type `composer install`
 - start `xampp` application
 - start `apache` and `mysql`
 - Go to your browser and type `localhost/phpmyadmin`
 - Create a database called `tinyerp`
 - Back to the project root folder and duplicate `.env.example` file and rename it to `.env`
 - Then in the terminal type `php artisan key:generate`
 - Open the `.env` file in the text editor
 - Change the `DB_DATABASE` value to `tinyerp` 
 - Change the `DB_USERNAME` value to `root` 
 - Left blank on `DB_PASSWORD` and save it.
 - Type `php artisan migrate --seed` 
 - Then type `php artisan passport:install`
 - The one user will be inserted with username `admin@tinyerp.com` and password `admin@12345`
 - Open the file `xampp/apache/conf/http.conf`, change `C:/xampp/htdocs/` to `C:/xampp/htdocs/{yourprojectname}/public`
 - Restart the `XAMPP`
 - You can access the application in the browser @ `localhost/{yourproject-folder-name}/public`


# To dos
 - Dashboard
 - User account CRUD
 - Purchasing Module
