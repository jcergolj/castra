![Github Actions](https://github.com/jcergolj/castra/actions/workflows/main.yml/badge.svg)

# Laravel Admin dashboard
Like Laravel Jetstream but built with Hotwire Turbo + additional perks.

Tools used:
- [tailwindcomponents/dashboard](https://github.com/tailwindcomponents/dashboard)
- [Hotwire Turbo](https://turbo.hotwired.dev)
- [Apinejs 3.0](https://alpinejs.dev/)

## Contents
- [Intro](#intro)
- [Installation](#installation)
- [Care for the code](#care-for-the-code)
- [Licence](#licence)

## Intro
It's like Laravel Jetstream however built with Hotwire Turbo and AlpineJs instead of Livewire.

* Laravel 8.0 :heavy_check_mark:
* Alpinejs 3.0 :heavy_check_mark:
* Hotwire Turbo 7.0 :heavy_check_mark:
* tailwindcomponents/dashboard theme :heavy_check_mark:
* Login :heavy_check_mark:
* Forgot Password :heavy_check_mark:
* 2 role authorisation system admin, member :heavy_check_mark:
* CRUD for Users :heavy_check_mark:
* Welcome email for a new user with a link for setting up a new password :heavy_check_mark:
* Profile with change password, email and user's image option :heavy_check_mark:
* Confirmation email to confirm a new user's email :heavy_check_mark:
* CI included (github actions) :heavy_check_mark:
* Larastan :heavy_check_mark:
* PHPCS :heavy_check_mark:
* Phpinsights :heavy_check_mark:
* Over 200 tests included :heavy_check_mark:

## Installation
After installing <a href="https://laravel.com/docs/8.0/">Laravel</a> you should run those commands:
```
git clone https://github.com/jcergolj/castra.git
composer install
cp .env.example .env
php artisan key:generate
npm install
npm run dev
php artisan migrate:fresh --seed
```

## Care for the code
Let's face it. Sometimes we are sloppy, and we don't take the best care of the code.
Here are some tools available that can help you and they are run automatically when you pushed to the repository or you can run them manually as composer commands.
Let's look at them:
- phpcs: php code style formatter
`composer phpcs`

- prettier: js code formatter
`composer jsprettier`

- php insights: PHP code quality analyser
`composer insights`

- phpstan:  PHP static analysis tool
`composer phpstan`

## License
Licensed under the [MIT license](https://github.com/deployphp/deployer/blob/master/LICENSE)
