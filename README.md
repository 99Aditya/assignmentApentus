Coading Approach:
Set up a new Laravel project.
Create a new database in phpMyAdmin named assignment.
Create models and migration files within the project for the required tables.
Define necessary fields in the migration files for proper database structure.
Create seeders to populate the database with sample data.
Develop controllers for all modules to handle the business logic.
Implement authentication using Sanctum for API authentication.
Define routes in the api.php file for the required endpoints.
Develop category and task functions along with their corresponding routes.
Test the APIs by exporting the Postman collection and running them.
Write test cases for all controllers to ensure proper functionality.

Project setup and run steps
Step 1: Clone the Project
Clone the project repository using the following command: git clone https://github.com/99Aditya/assignmentApentus.git

Step 2: Create a new database in phpMyAdmin named is "assignment".

Step 3: Run the migration and seeder commands to set up the database schema and insert seed data using the following command.
        php artisan migrate:fresh --seed
        or
        php artisan migrate
        php artisan db:seed

Step 4: Run the Project using the following command:
    php artisan serve

Step 5: Export the Postman collection and run the API endpoints.
