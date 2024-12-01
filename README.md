Coading Approach:
Step 1: Set up a new Laravel project.

Step 2: Create a new database in phpMyAdmin named assignment.

Step 3: Create models and migration files within the project for the required tables.

Step 4: Define necessary fields in the migration files for proper database structure.

Step 5: Create seeders to populate the database with sample data.

Step 6: Develop controllers for all modules to handle the business logic.

Step 7: Implement authentication using Sanctum for API authentication.

Step 8: Define routes in the api.php file for the required endpoints.

Step 9: Develop category and task functions along with their corresponding routes.

Step 10: Test the APIs by exporting the Postman collection and running them.

Step 11: Write test cases for all controllers to ensure proper functionality.

Project setup and run steps

Step 1: Create a new database in phpMyAdmin named is "assignment".

Step 2: Run the migration and seeder commands to set up the database schema and insert seed data using the following command.
        php artisan migrate:fresh --seed
        or
        php artisan migrate
        php artisan db:seed

Step 3: Run the Project using the following command:
    php artisan serve

Step 4: Export the Postman collection and run the API endpoints.
