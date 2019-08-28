# todo-app

## Installation

1. `git clone https://github.com/MantasPeldzius/todo-app.git` (add `.` if clone into current directory)
2. `composer install`
3. Create `.env` from `.env.example`
4. Create Database
5. Fill required configuration in `.env` for database connection (disable stric mode with `DB_STRICT_MODE=false`)
6. Add required configurations for JWT and Mail
```
JWT_SECRET=your_jwt_secret

MAIL_DRIVER=
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=
MAIL_FROM_ADDRESS=
MAIL_FROM_NAME=
```
7. Create tables `php artisan migrate:fresh`
8. Fill `users` table with users for testing `php artisan db:seed --class=UsersTableSeeder`
9. Make sure that webservice can write in storage folder

## Usage

1. From index user can request password change (update email of user in database)
2. Login user - `POST */user/login` array(user, password), result login token
3. Create user - `POST */user/create` array(user, password, email), result created user
4. Show all tasks (need login token) - `GET */api/tasks/{id}` 
5. Show one task (need login token) - `GET */api/tasks`
6. Create task (need login token) - `POST */api/tasks/{id}` array(caption, text)
7. Delete task (need login token) - `DELETE */api/tasks/{id}`
8. Update task (need login token) - `PUT */api/tasks/{id}` array(caption, text)
9. Show full users log (need login token) - `GET */api/user/log`

