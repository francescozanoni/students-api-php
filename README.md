# Student management API - PHP [![Build Status](https://travis-ci.org/francescozanoni/students-api-php.svg?branch=master)](https://travis-ci.org/francescozanoni/students-api-php)

This is a [RESTful](https://en.wikipedia.org/wiki/Representational_state_transfer) API providing generic management functionalities of university students:

- students
- annotations (usually managed by tutors)
- stages
  - evaluations
  - interruption reports
- seminars
- additional educational activities

The API is documented with [OpenAPI](https://swagger.io/docs/specification/about/) specification (version 3.0) and can be accessed via [Swagger UI](https://swagger.io/tools/swagger-ui) interface at URL **/docs**.

----

### Installation

#### Dependency installation

```bash
composer install
```

#### Set up

```bash
# To use default URL http://localhost
php scripts/setup.php

# To use custom URL
# php scripts/setup.php --application_url=<APPLICATION_URL>

# To add phpLiteAdmin, reachable at URL <APPLICATION_URL>/phpliteadmin/phpliteadmin.php
# php scripts/setup.php --with_phpliteadmin

php artisan migrate
```

### Uninstallation

#### Dependency uninstallation

```bash
rm -rf vendor
rm -rf public/docs
```

#### Set down

```bash
php scripts/setdown.php
```

### Local deployment

```bash
php -S localhost:80 -t public public/router.php
```
