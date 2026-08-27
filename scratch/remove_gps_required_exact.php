<?php
require __DIR__ . '/../vendor/autoload.php';

$dir = __DIR__ . '/../resources/views/owner/properties';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $lines = file($file->getPathname());
        $modified = false;

        for ($i = 0; $i < count($lines); $i++) {
            if (str_contains($lines[$i], 'gps_latitude') || str_contains($lines[$i], 'gps_longitude')) {
                // Check current line and next 2 lines
                for ($j = max(0, $i - 1); $j <= min(count($lines) - 1, $i + 2); $j++) {
                    if (str_contains($lines[$j], 'required')) {
                        $lines[$j] = str_replace(' required', '', $lines[$j]);
                        $lines[$j] = str_replace('required', '', $lines[$j]);
                        $modified = true;
                    }
                }
            }
        }

        if ($modified) {
            file_put_contents($file->getPathname(), implode('', $lines));
            echo "Stripped GPS required lines in: " . $file->getFilename() . "\n";
        }
    }
}
