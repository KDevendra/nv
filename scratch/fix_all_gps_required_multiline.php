<?php
require __DIR__ . '/../vendor/autoload.php';

$dir = __DIR__ . '/../resources/views/owner/properties';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());

        // Remove required attribute from gps_latitude and gps_longitude across multiple lines or single line
        $updated = preg_replace_callback('/(<input[^>]*name=["\'](?:gps_latitude|gps_longitude)["\'][^>]*>)/is', function ($matches) {
            $tag = $matches[0];
            return preg_replace('/\s+required(=["\'][^"\']*["\'])?/i', '', $tag);
        }, $content);

        // Also check if required is on the same input tag spanning multiline
        $updated = preg_replace_callback('/(name=["\'](?:gps_latitude|gps_longitude)["\'][^>]*)\s+required/is', function ($matches) {
            return $matches[1];
        }, $updated);

        $updated = preg_replace_callback('/required\s+([^>]*name=["\'](?:gps_latitude|gps_longitude)["\'])/is', function ($matches) {
            return $matches[1];
        }, $updated);

        if ($updated !== $content) {
            file_put_contents($file->getPathname(), $updated);
            echo "Cleaned multiline GPS required in: " . $file->getFilename() . "\n";
        }
    }
}
