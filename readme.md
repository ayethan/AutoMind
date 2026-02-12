# AutoMind ERP System

This project is a general-purpose Tiny ERP system, "AutoMind," designed to manage various aspects of a business, including products, sales, customers, and more. It is powered by the Laravel framework.

## Table of Contents
- [Project Overview](#project-overview)
- [Tech Stacks](#tech-stacks)
- [Architectural Overview](#architectural-overview)
- [Installation for Local Network](#installation-for-local-network)
  - [Prerequisites](#prerequisites)
  - [Clone the Project](#clone-the-project)
  - [Backend Setup (Choose your database)](#backend-setup-choose-your-database)
    - [Option 1: Using SQLite](#option-1-using-sqlite)
    - [Option 2: Using MySQL (with XAMPP)](#option-2-using-mysql-with-xampp)
  - [Frontend Setup](#frontend-setup)
  - [Configure Web Server (XAMPP Apache Example)](#configure-web-server-xampp-apache-example)
  - [Access the Application](#access-the-application)
- [Usage](#usage)
- [Project Structure](#project-structure)
- [Contribution Guidelines](#contribution-guidelines)
- [To Dos](#to-dos)

## Project Overview
AutoMind is a web-based Enterprise Resource Planning (ERP) solution built with Laravel. It provides modules for managing product inventory, processing sales, tracking expenses, handling customer information, and more. The system aims to offer a robust and extensible foundation for small to medium-sized businesses.

## Tech Stacks
-   Laravel 11.48.0
-   MySQL 5.7+ (Recommended with XAMPP, version may vary) (Optional)
-   SQLite (Optional)
-   PHP 8.4.1
-   Vue.js (for frontend components, as suggested by `lara-vue` directory)

## Architectural Overview
This project adheres to modern software design principles, specifically implementing the **Repository and Service Patterns** to ensure a clean separation of concerns and maintainability. This architecture also aligns with **SOLID principles**:

-   **Repository Pattern:** Data access logic (e.g., fetching, storing, updating, deleting records) is encapsulated within dedicated Repository classes (e.g., `ProductRepository`, `SaleRepository`). This abstracts the data source from the business logic, making it easier to swap out ORMs or databases in the future without affecting the core application.
-   **Service Layer:** Business logic and complex operations are housed in Service classes (e.g., `ProductService`, `SaleService`). These services coordinate between one or more repositories, perform validation, and execute multi-step processes. Controllers interact solely with these services, keeping them thin and focused on handling HTTP requests and responses.

This approach significantly enhances testability, modularity, and scalability of the application.

## Installation for Local Network

### Prerequisites
-   Install the latest stable [Composer](https://getcomposer.org/) (PHP dependency manager).
-   Ensure Node.js and npm (Node Package Manager) are installed for frontend dependencies.
-   **(Optional, for MySQL):** Install the latest stable [XAMPP](https://www.apachefriends.org/index.html) (includes Apache, MySQL, PHP).

### Clone the Project
1.  Clone the project from your Git repository to your web server's document root (e.g., `xampp/htdocs` for XAMPP users).
2.  Rename the cloned folder to your desired project name (e.g., `AutoMind`).

### Backend Setup (Choose your database)
Navigate to your project's root directory in your terminal (`cd C:/xampp/htdocs/AutoMind` or similar).

1.  Run `composer install` to install PHP dependencies.
2.  Duplicate the `.env.example` file and rename it to `.env`.
3.  Generate an application key: `php artisan key:generate`.
4.  Open the `.env` file in a text editor to configure your database:

#### Option 1: Using SQLite
*   In your project's `database/` directory, create an empty file named `database.sqlite`.
*   Modify your `.env` file as follows:
    ```
    DB_CONNECTION=sqlite
    DB_DATABASE=/absolute/path/to/your/project/database/database.sqlite
    # Comment out or remove MySQL-specific variables
    # DB_HOST=127.0.0.1
    # DB_PORT=3306
    # DB_USERNAME=root
    # DB_PASSWORD=
    ```
    *Make sure to provide the full absolute path to your `database.sqlite` file.*

#### Option 2: Using MySQL (with XAMPP)
*   Start your XAMPP application (Apache and MySQL services).
*   Go to your browser and open `localhost/phpmyadmin`. Create a new database named `tinyerp`.
*   Modify your `.env` file as follows:
    ```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=tinyerp
    DB_USERNAME=root         # Or your MySQL username
    DB_PASSWORD=             # Your MySQL password (leave blank if none)
    ```

5.  **Run Migrations and Seeders:**
    *   `php artisan migrate --seed`
6.  **Install Laravel Passport:**
    *   `php artisan passport:install`
    *   _Note:_ A default user will be inserted with username `admin@tinyerp.com` and password `admin@12345`.

### Frontend Setup
1.  In your project's root directory in the terminal, run `npm install` to install JavaScript dependencies.
2.  Compile frontend assets:
    *   For development: `npm run dev`
    *   For production: `npm run prod`

### Configure Web Server (XAMPP Apache Example)
If you're using XAMPP's Apache, you'll want to configure it to serve your project's `public` directory.
1.  Open the `xampp/apache/conf/httpd.conf` file.
2.  Change the `DocumentRoot` and `<Directory>` directives to point to your project's `public` folder. For example, if your project folder is `AutoMind`:
    ```apache
    DocumentRoot "C:/xampp/htdocs/AutoMind/public"
    <Directory "C:/xampp/htdocs/AutoMind/public">
        AllowOverride All
        Require all granted
    </Directory>
    ```
3.  Restart the Apache service in XAMPP.

### Access the Application
-   You can now access the application in your browser by navigating to `localhost`.

## Usage
Once installed, the application exposes a RESTful API. You can interact with it using tools like Postman or by building a frontend client.

**Default Admin Credentials:**
-   **Username:** `admin@tinyerp.com`
-   **Password:** `admin@12345`

Use these credentials to obtain an OAuth token via Laravel Passport, which will then allow you to access protected API endpoints.

## Project Structure
Key directories and their roles:

-   `app/Http/Controllers/API`: Contains the API controllers, now lean and interacting primarily with the Service layer.
-   `app/Repositories`: Houses the Repository classes, responsible for abstracting data access logic from the application.
-   `app/Services`: Contains the Service classes, where the core business logic and orchestration of repositories reside.
-   `database/migrations`: Database schema definitions.
-   `database/seeds`: Initial data for the database.
-   `database/database.sqlite`: The SQLite database file (if using SQLite).
-   `public`: The web server's document root.
-   `resources/js`: Frontend JavaScript files, including Vue.js components.
-   `routes/api.php`: API route definitions.

## Contribution Guidelines
Contribution guidelines will be added in a future update. Please adhere to the existing code style and architectural patterns.

## To Dos
-   Dashboard
-   User account CRUD
-   Purchasing Module
