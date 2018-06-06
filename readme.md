## Vuejs Laravel Resource ##

is a Single Page Realtime Application.

Use login: `admin@gmail.com` and password: `123456`

### Installation ###
```
# Install dependecies
npm install
composer install

# Prepare enviroment variables
cp .env.example .env # Copy configuration file
php artisan key:generate # Generate unique key

# Update .env file with Pusher key
Create/Login account in https://pusher.com/ to get a key

# Migrate database
php artisan migrate --seed # Create Table Schema and seed sample data
php artisan passport:install # Install Passport
```

### Features ###

* Fully separate Backend and Frontend
* Material design
* Authentication based on Laravel Passport
* CRUD data
* Realtime Notification on CRUD

### Includes ###

* [Laravel Passport](https://laravel.com/docs/5.4/passport) API Authentication
* [Vue.js](https://vuejs.org/) The Progressive JavaScript Framework
* [Pusher](https://pusher.com/) API for Realtime Application
* [Vuetify](https://vuetifyjs.com/en/) Frontend Framework
* [Vuex](https://vuex.vuejs.org/en/intro.html) State management pattern + library for Vue.js
* [Vue-Router](https://router.vuejs.org/en/) Router library for Vue.js
* [Axios](https://github.com/axios/axios) HTTP client
