<p align="center">
    <h1 align="center">Task Tracker</h1>
    <br>
</p>

A simple task tracking tool for managing tasks with support for creation, editing, deletion, and status updates. Logged-in users can create, edit (name and deadline), delete tasks, toggle their completion status, and access the task list via API (JSON) or in the browser.

SETUP INSTRUCTIONS
-------------------
1. Clone the repository.
2. Run `composer install` from the project's root directory.
3. Create an empty database.
4. Set up database credentials in `environments/prod/common/config/main-local.php`.
5. Run `php init` and apply `Production` mode.
6. Apply migrations with `php yii migrate`.
7. Run `php -S localhost:8080 -t frontend/web`.
8. Open `http://localhost:8080` in the browser.

API ACCESS GUIDE
-------------------
You can access user-specific tasks in JSON format via a web browser or API tools like Postman.

<h3>View tasks in browser</h3>

    [GET] http://localhost:8080/task/api-tasks

<h3>View tasks in Postman</h3>

1. <strong>Authenticate:</strong>

        [POST] http://localhost:8080/api/login

    Body (`form-data`):
    | Key  | Value |
    | ------------- | ------------- |
    | username  | YOUR_USERNAME  |
    | password  | YOUR_PASSWORD  |

    Successful Response:

        {
            "success": true,
            "access_token": "TPu06PBHkGx7bSD8GydvyFMim-iyVyzo",
            "message": "Login successful"
        }

2. <strong>Get user tasks:</strong>

        [GET] http://localhost:8080/task/api-tasks
    Headers:

        Authorization: Bearer YOUR_ACCESS_TOKEN
