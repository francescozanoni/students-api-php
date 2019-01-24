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

```bash
# Dependencies
composer install

# Static files

#  - to use default URL http://localhost
php scripts/setup.php

#  - to use custom URL
# php scripts/setup.php --application_url=<APPLICATION_URL>

#  - to add phpLiteAdmin, reachable at URL <APPLICATION_URL>/phpliteadmin/phpliteadmin.php
# php scripts/setup.php --with_phpliteadmin

# Database structure
php artisan migrate
```

### Uninstallation

```bash
# Dependencies
rm -rf vendor
rm -rf public/docs

# Static files
php scripts/setdown.php
```

### Local deployment

```bash
php -S localhost:80 -t public public/router.php
```
