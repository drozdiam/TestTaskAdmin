<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>


# Установка проекта

Клонируем репозиторий:

`git clone https://github.com/drozdiam/TestTaskAdmin.git`

Обновляем все зависимости:

`composer update`

Переименовываем файл .env:

`.env.example в .env`

Размещаем файл sqlite (если используется sqlite):

`database/database.sqlite`

Устанавливаем путь локального хранилища:

`FILESYSTEM_DRIVER=public`


Создаем папку images в директории:

`/storage/app/public/images`

Создаём ссылку на хранилище в public:

`php artisan storage:link`

Генерируем ключ:

`php artisan key:generate`

Делаем миграцию и запускаем сид:

`php artisan migrate --seed`

Устанавливаем js библиотеки :

`npm install`

Компилируем css и js:

`npm run dev`

Запускаем сервер:

`php artisan serve`

Выбираем почту любого пользователя из таблицы users.

Пароль создаться для всех пользователей по умолчанию:

`123456789`

Готово! =)
