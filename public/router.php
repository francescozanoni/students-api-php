<?php
/**
 * Router script for PHP Built-in Web Server
 *
 * To be booted by:
 *   $ cd path/to/{application}
 *   $ php -S localhost:80 -t public public/router.php
 *
 * @see http://arr.gr/blog/2012/08/serving-zf-apps-with-the-php-54-built-in-web-server/
 */
if (php_sapi_name() === 'cli-server') {

    $reqUri = $_SERVER['REQUEST_URI'];
    if (strpos($reqUri, '?') !== false) {
        $reqUri = substr($reqUri, 0, strpos($reqUri, '?'));
    }

    $target = realpath(__DIR__ . $reqUri);
    if ($target && is_file($target)) {
        // Security check: make sure the file is under the public dir
        if (strpos($target, __DIR__) === 0) {
            // Tell PHP to directly serve the requested file
            return false;
        }
    }

}

// Load the ZF app front controller script
require __DIR__ . '/index.php';
