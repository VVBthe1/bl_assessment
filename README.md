To setup the application follow these steps

NON DOCKER Application Testing

Backend
- take a clone of this repository
- install composer if not already done using guidelines here: https://getcomposer.org/download/
- navigate to rest-backend folder and run "composer install"
- copy .env.example to .env
- setup the mysql credentials in DB section of .env file.
- generate environment key executing "php artisan key:generate"
- migrate the db by executing "php artisan migrate"
- run seeders by executing "php artisan db:seed". this will create a user that we use for login via the frontend app. as well will create some dummy data for the listing.
- navigate back to the project root directory
- use PHP server command to start a development server by executing "php -S 0.0.0.0:8000 -t rest-backend/public/"
- If you use another port then the frontend file endpoint needs to be modified to incorporate the same.
- By now our backend application should be up and running.

Frontend
- frontend application is clubbed with the backend service and the same port can be used to browse the application
- navigate to http://localhost:8000/index.html in your browser of choice. change the port if you have modified the backend app port.
- play around

What is done
- Authentication & Token based Authorization 
- CRUD endpoints
- API validations
- Migrations
- Seeders
- Frontedn to backed integeations for all the crud operations
- sortable listing
- docker and docker compose file for containerization

What was not done
- working dockerized application. Was facing CORS and DB connection related issues.
