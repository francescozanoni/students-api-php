# Student management API - PHP [![Build Status](https://travis-ci.org/francescozanoni/students-api-php.svg?branch=master)](https://travis-ci.org/francescozanoni/students-api-php)

This is a [RESTful](https://en.wikipedia.org/wiki/Representational_state_transfer) API providing generic management functionalities of university students:

- students
- annotations (usually managed by tutors)
- stages
  - evaluations
  - interruption reports
- educational activity attendances

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

# Edit .env with stage evaluation stage item details...

# Configuration of OpenAPI schema
php artisan openapi:configure

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
