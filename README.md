<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## О Laravel

Laravel – фреймворк веб-приложения с выразительным, элегантным синтаксисом. Веб-фреймворк предлагает структуру и отправную точку для создания вашего приложения, позволяя вам сосредоточиться на создании чего-то удивительного, но пока мы не будем вдаваться в детали.
Laravel стремится обеспечить потрясающий опыт разработчика, предоставляя при этом мощный функционал: тщательное внедрение зависимостей, выразительный уровень абстракции базы данных, очереди и запланированные задачи, модульное и интеграционное тестирование и многое другое.

В тестовом проекте используется laravel version v.9

## установка Laravel

1)Установка через Composer

~~~
composer create-project laravel/laravel internet-blog
~~~

2)Установка компонентов laravel для авторизации/регистрации

~~~
composer require laravel/ui
~~~
~~~
php artisan ui vue --auth
~~~
Эта команда используется для генерации шаблонов регистрации, входа, выхода и сброса пароля, а также добавления маршрутов аутентификации. Шаблоны будут расположены в отдельной папке resources/views/auth. Команда ui также создаст папку resources/views/layouts, содержащую базовый шаблон для вашего приложения, основанный на CSS-фреймворке Bootstrap.

3)Установка стилей для авторизации/регистрации laravel/ui

~~~
npm install
npm run dev
~~~
Установка vue-router v3
~~~
npm install vue-router ^3
~~~
Установка vuex v3
~~~
npm install vuex ^3
~~~

## Настройка соединения с базой данных

Редактировать файл `/.env`:

```
APP_NAME=Mebel-shop
APP_ENV=local
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=Laravel
DB_USERNAME=root
DB_PASSWORD=root

MAIL_MAILER=smtp
MAIL_HOST=smtp.googlemail.com
MAIL_PORT=465
MAIL_USERNAME=jjnn95555@gmail.com
MAIL_PASSWORD="12312341324141"
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=jjnn95555@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```
## Запуск проекта на локальном сервере(OpenServer v5.4.0)
  Перейти в папку с проектом и в консоли openServer или windows ввести команду
  
~~~
php artisan serve
~~~

Запуск компилятора фронтенда vuejs
~~~
npm run watch
~~~
