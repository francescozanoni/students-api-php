<?php
/**
 * User: Francesco.Zanoni
 * Date: 17/09/2018
 * Time: 14:09
 */

$sourcePath = 'vendor/swagger-api/swagger-ui/dist';

// Keep the following path aligned with file config/openapi.php.
$destinationPath = 'public/docs';

if (file_exists($destinationPath) === true && is_dir($destinationPath) === true) {
    array_map('unlink', glob($destinationPath . '/*'));
    rmdir($destinationPath);
}
mkdir($destinationPath);
foreach (glob($sourcePath . '/*') as $file) {
    copy($file, $destinationPath . '/' . basename($file));
}