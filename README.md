# Student management API - PHP [![Build Status](https://travis-ci.org/francescozanoni/students-api-php.svg?branch=master)](https://travis-ci.org/francescozanoni/students-api-php) [![Coverage Status](https://coveralls.io/repos/github/francescozanoni/students-api-php/badge.svg?branch=master&service=github)](https://coveralls.io/github/francescozanoni/students-api-php?branch=master&service=github)

This is a [RESTful](https://en.wikipedia.org/wiki/Representational_state_transfer) API providing generic management functionalities of university students:

- students
- annotations (usually managed by tutors)
- internships
  - eligibilities
  - [occupational safety and health](https://en.wikipedia.org/wiki/Occupational_safety_and_health) course attendances
  - evaluations
  - interruption reports
- educational activity attendances

![Entity-relationship diagram](/er_diagram.png)

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

# Edit .env with internship evaluation item details...

# Configuration of OpenAPI schema
php artisan openapi:configure

# Database structure
php artisan migrate

# [OPTIONAL] Test data
php artisan db:seed
```

If test data are not used, below tables must be filled with below Artisan commands:

- countries
- locations
- sub_locations

```bash
php artisan country:add "United States of America" US
php artisan location:add "Location A"
php artisan sublocation:add "Sub-location A"
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
