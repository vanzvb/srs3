## Auth

1. Install Laravel UI Package:

composer require laravel/ui


2. Generate Authentication Scaffolding:

    After installing the package, you can generate the authentication scaffolding using one of the following commands:

    For Bootstrap: (used)
    php artisan ui bootstrap --auth

    For Vue.js:
    php artisan ui vue --auth

    For React:
    php artisan ui react --auth

3. Install Frontend Dependencies:
After generating the scaffolding, you'll need to install the frontend dependencies:

npm install

4. Build the Frontend Assets:
Then, build the assets using:

npm run dev

5. Run Database Migrations:
To create the necessary tables for authentication (like users), run the migrations:

php artisan migrate

6. Serve the Application:
Finally, you can serve the application:

php artisan serve

## Troubleshooting
If you're still encountering issues, make sure you're using a compatible Laravel version and that your Composer dependencies are up to date:

composer update
