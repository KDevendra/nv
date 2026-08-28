<?php
require __DIR__ . '/../vendor/autoload.php';

$dir = __DIR__ . '/../app/Http/Controllers/Owner/PropertyEntry';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());

        $updated = preg_replace("/'project_name'\s*=>\s*'nullable\|required_if:[^']+'/i", "'project_name' => 'nullable|string|max:120'", $content);
        $updated = preg_replace("/'project_society_name'\s*=>\s*'nullable\|required_if:[^']+'/i", "'project_society_name' => 'nullable|string|max:120'", $updated);
        $updated = preg_replace("/'possession_by'\s*=>\s*'nullable\|required_if:[^']+'/i", "'possession_by' => 'nullable|string|max:120'", $updated);
        $updated = preg_replace("/'possession_by_if_under_constr'\s*=>\s*'nullable\|required_if:[^']+'/i", "'possession_by_if_under_constr' => 'nullable|string|max:120'", $updated);
        $updated = preg_replace("/'available_from'\s*=>\s*'nullable\|required_if:[^']+'/i", "'available_from' => 'nullable|string|max:120'", $updated);

        if ($updated !== $content) {
            file_put_contents($file->getPathname(), $updated);
            echo "Cleaned backend validation in: " . $file->getFilename() . "\n";
        }
    }
}
