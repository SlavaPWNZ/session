Тестовое задание<br><br>
Есть таблица Session. 
id, user_id, login_time, logout_time
В ней хранится id пользователя, время входа и время выхода из системы (может быть null, если визит еще не завершен). Определить в какое время за отдельно взятые сутки в системе находилось одновременно максимальное число пользователей. 
Ответ: Скрипт на языке PHP, на вход принимает дату. Будут учитываться красота и оптимальность  SQL запроса и алгоритма расчета, а также полнота выдаваемого скриптом ответа.<br>


**Установка:**

1) git clone https://github.com/SlavaPWNZ/session.git
2) composer install
3) создать файл .env в корне проекта. Скопировать туда 
код из .env.example и поменить данные на нужные... 
(APP_URL, DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD)
4) php artisan migrate
5) php artisan db:seed --class=SessionTableSeeder
6) php artisan key:generate

Пример запуска скрипта:
http://session.test/2020-04-28
