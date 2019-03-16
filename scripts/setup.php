#!/usr/bin/env php
<?php

define('BASE_PATH', realpath(__DIR__ . '/..'));
require_once BASE_PATH . '/vendor/autoload.php';

# #####################################################

# INSTALLATION OPTIONS

# Default values
$options = [
    'application_url' => 'http://localhost', // Laravel's default
];

# If provided, input values override default values
$inputOptions = getopt('', ['application_url:']);
if (isset($inputOptions['application_url']) === true) {
    if (filter_var($inputOptions['application_url'], FILTER_VALIDATE_URL) === false) {
        die('Invalid application URL' . PHP_EOL);
    }
    $options['application_url'] = $inputOptions['application_url'];
}

# #####################################################

# FILE/FOLDER PATH CONSTANTS

define('HTACCESS_FILE_PATH', BASE_PATH . '/public/.htaccess');
define('HTACCESS_EXAMPLE_FILE_PATH', BASE_PATH . '/public/.htaccess.example');
define('DATABASE_FILE_PATH', BASE_PATH . '/database/database.sqlite');
define('DOT_ENV_FILE_PATH', BASE_PATH . '/.env');
define('DOT_ENV_EXAMPLE_FILE_PATH', BASE_PATH . '/.env.example');
define('PHPUNIT_XML_FILE_PATH', BASE_PATH . '/phpunit.xml');
define('PHPUNIT_XML_EXAMPLE_FILE_PATH', BASE_PATH . '/phpunit.xml.example');
define('OPENAPI_FILE_PATH', BASE_PATH . '/public/openapi.yaml');
define('OPENAPI_EXAMPLE_FILE_PATH', BASE_PATH . '/public/openapi.yaml.example');

# #####################################################

# FILE INEXISTENCE CHECK

$filePathsToCheck = [
    HTACCESS_FILE_PATH,
    DATABASE_FILE_PATH,
    DOT_ENV_FILE_PATH,
    PHPUNIT_XML_FILE_PATH,
    OPENAPI_FILE_PATH,
];

foreach ($filePathsToCheck as $filePath) {
    if (file_exists($filePath) === true) {
        die($filePath . ' already exists' . PHP_EOL);
    }
}

# #####################################################

echo 'Setting up directories...' . PHP_EOL;

# Directories writable by the web user
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('storage'));
foreach ($iterator as $item) {
    if (basename($item) === '.gitignore') {
        continue;
    }
    chmod($item, 0777);
}
chmod(BASE_PATH . '/storage', 0775);

# #####################################################

echo 'Setting up database...' . PHP_EOL;

# Database files
touch(DATABASE_FILE_PATH);
chmod(DATABASE_FILE_PATH, 0777);
chmod(dirname(DATABASE_FILE_PATH), 0777);
echo PHP_EOL;

# #####################################################

echo 'Setting up configuration files...' . PHP_EOL;

# Web server-related configuration
copy(HTACCESS_EXAMPLE_FILE_PATH, HTACCESS_FILE_PATH);

# Configuration files
copy(DOT_ENV_EXAMPLE_FILE_PATH, DOT_ENV_FILE_PATH);
copy(PHPUNIT_XML_EXAMPLE_FILE_PATH, PHPUNIT_XML_FILE_PATH);

# OpenAPI files
copy(OPENAPI_EXAMPLE_FILE_PATH, OPENAPI_FILE_PATH);

# #####################################################

# Base URL and database file path setting
$file = file_get_contents(DOT_ENV_FILE_PATH);
$file = preg_replace('#http://localhost#', $options['application_url'], $file);
$file = preg_replace('#/absolute/path/to/database/database.sqlite#', realpath(DATABASE_FILE_PATH), $file);
$file = preg_replace('#APP_KEY=#', 'APP_KEY=' . md5(date('now')), $file);
file_put_contents(DOT_ENV_FILE_PATH, $file);

$file = file_get_contents(PHPUNIT_XML_FILE_PATH);
$file = preg_replace('#http://localhost#', $options['application_url'], $file);
file_put_contents(PHPUNIT_XML_FILE_PATH, $file);

$file = file_get_contents(OPENAPI_FILE_PATH);
$file = preg_replace('#http://localhost#', $options['application_url'], $file);
file_put_contents(OPENAPI_FILE_PATH, $file);

$baseUrl = parse_url($options['application_url'], PHP_URL_PATH);
if (empty($baseUrl) === true) {
    $baseUrl = '/';
}
$file = file_get_contents(HTACCESS_FILE_PATH);
$file = preg_replace('#RewriteBase\s/#', 'RewriteBase ' . $baseUrl, $file);
file_put_contents(HTACCESS_FILE_PATH, $file);

# #####################################################

# Evaluation item customization within OpenAPI schema
// use Symfony\Component\Yaml\Yaml;
// $app = require BASE_PATH . '/bootstrap/app.php';
// print_r($app['config']['app']['evaluations']['items']);
// $s = Yaml::parseFile(OPENAPI_FILE_PATH);
// $s['components']['responses']['Evaluations']['content']['application/json']['schema']['example']['data'][0,1]
// $s['components']['responses']['Evaluation']['content']['application/json']['schema']['example']['data']
// $s['components']['schemas']['NewEvaluation']['properties']
// $s['components']['schemas']['NewEvaluation']['required']
// $s['components']['schemas']['NewEvaluation']['example']
// $s['components']['schemas']['Evaluation']['example']
// file_put_contents(OPENAPI_FILE_PATH, Yaml::dump($s));

# #####################################################

echo PHP_EOL;
