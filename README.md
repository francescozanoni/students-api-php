# students-api-php


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
