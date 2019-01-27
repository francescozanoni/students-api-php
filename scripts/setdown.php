#!/usr/bin/env php
<?php

define('BASE_PATH', realpath(__DIR__ . '/..'));

$filePathsToDelete = [

    BASE_PATH . '/public/.htaccess',

    BASE_PATH . '/database/database.sqlite',

    BASE_PATH . '/.env',

    BASE_PATH . '/phpunit.xml',
    
    BASE_PATH . '/public/openapi.yaml',

];

// Check all mandatory file paths are correct: if not found, execution is interrupted.
foreach ($filePathsToDelete as $filePath) {
    if (file_exists($filePath) === false) {
        die($filePath . ' does not exist' . PHP_EOL);
    }
    if (is_writable($filePath) === false) {
        die($filePath . ' is not writable' . PHP_EOL);
    }
    if (is_file($filePath) === false) {
        die($filePath . ' is not a file' . PHP_EOL);
    }
}

// Delete file paths.
foreach ($filePathsToDelete as $filePath) {
    unlink($filePath);
}

