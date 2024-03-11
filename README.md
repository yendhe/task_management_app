# Task Management app

Welcome to the Task Management app!

## Test Login Credentials

You can use the following credentials to log in to the application:

    "email": "riddhi@gmail.com",
    "password": "riddhi@123"

Please note that these are test credentials for testing purposes only.

## Installation

Follow these steps to install and run the project:

1. Clone the repository: `git clone <repository-url>`
2. Navigate to the project directory: `cd task_management_application`
3. Install dependencies: `composer install`
4. Set up environment variables: `cp .env.example .env`
5. Configure database settings in `.env` file
                                    DB_CONNECTION=mysql
                                    DB_HOST=127.0.0.1
                                    DB_PORT=3306
                                    DB_DATABASE=task_management_app
                                    DB_USERNAME=root
                                    DB_PASSWORD=
    after setup env variables optimize cache
    run ->`php artisan optimize`
6. Generate application key: `php artisan key:generate`
                              `php artisan passport:keys` 
                              `php artisan passport:client --personal`
7. Run migrations: `php artisan migrate`
8. Start the development server: `php artisan serve`
9. Access the application in your browser: `http://localhost:8000`

apis and there responses ->
1. POST-> http://127.0.0.1:8000/api/login
{
    "email": "riddhi@gmail.com",
    "password": "riddhi@123"
}

2. POST -> http://127.0.0.1:8000/api/register
{
    "name": "riddhi",
    "email": "riddhi@gmail.com",
    "password": "riddhi@123"
}

3. POST -> http://127.0.0.1:8000/api/tasks
in case page redirecting to home or validation part not working in postman 
You must set a header in your request: Accept => application/json
then it will return validation errors as json
{
    "subject": "math",
    "description": "test",
    "start_date": "2023-08-28",
    "due_date": "2023-09-30",
    "status": "New",
    "priority": "High",
    "notes": [
        {
            "subject": "test1",
            "note": "abc",
            "attachment": [
                "pass 1 base64 image",
                "pass 2 base64 image"
            ]
        }
    ]
}

4. GET-> http://127.0.0.1:8000/api/tasks?search_keywords=[{%22key%22:%22priority%22,%22value%22:%22hi%22},{%22key%22:%22status%22,%22value%22:%22new%22}]

5. POST -> http://127.0.0.1:8000/api/upload

form-data -->  attachments[] -> select multiple files


