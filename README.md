# Laravel Vue Starter 

![php-badge](https://img.shields.io/packagist/php-v/albertcht/lumen-vue-starter.svg)
[![packagist-badge](https://img.shields.io/packagist/v/albertcht/lumen-vue-starter.svg)](https://packagist.org/packages/albertcht/lumen-vue-starter)
[![Total Downloads](https://poser.pugx.org/albertcht/lumen-vue-starter/downloads)](https://packagist.org/packages/albertcht/lumen-vue-starter)
[![travis-badge](https://api.travis-ci.org/albertcht/lumen-vue-starter?branch=master)](https://travis-ci.org/albertcht/lumen-vue-starter)

> A Lumen-Vue SPA starter project template.

<p align="center">
<img src="https://i.imgur.com/NHFTsGt.png">
</p>

## Features

- Lumen 5.5
- Helpers in Laravel style
- Testing in Laravel style
- Tinker command
- Serve command
- Support Form Request Validation
- Vue + VueRouter + Vuex + VueI18n
- Pages with custom layouts 
- Login, register and password reset
- Authentication with JWT (Auto refresh)
- Socialite integration
- Bootstrap 4 + Font Awesome 5

## Installation

- `composer create-project --prefer-dist albertcht/lumen-vue-starter`
- Edit `.env` and set your database connection details
- `php artisan migrate`
- `php artisan jwt:secret`
- `yarn` / `npm install`

## Usage

### Development

```bash
# build and watch
yarn watch

# serve with hot reloading
yarn hot
```

### Production

```bash
yarn production

# or this
yarn build
```

## Reference

This is a porting version from [laravel-vue-spa](https://github.com/cretueusebiu/laravel-vue-spa)
