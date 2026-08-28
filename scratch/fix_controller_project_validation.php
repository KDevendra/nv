<?php
require __DIR__ . '/../vendor/autoload.php';

$dir = __DIR__ . '/../app/Http/Controllers/Owner/PropertyEntry';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());

        $updated = str_replace(
            "'builder_developer_name' => 'nullable|required_if:part_of_a_project_society,Yes|string|max:120',",
            "'builder_developer_name' => 'nullable|string|max:120',",
            $content
        );

        $updated = str_replace(
            "'developer_builder_name' => 'nullable|required_if:part_of_a_project_society,Yes|string|max:120',",
            "'developer_builder_name' => 'nullable|string|max:120',",
            $updated
        );

        $updated = str_replace(
            "'project_rera_id' => 'nullable|required_if:part_of_a_project_society,Yes|string|max:120',",
            "'project_rera_id' => 'nullable|string|max:120',",
            $updated
        );

        $updated = str_replace(
            "'rera_registration_id' => 'nullable|required_if:rera_registered,Yes|string|max:120',",
            "'rera_registration_id' => 'nullable|string|max:120',",
            $updated
        );

        if ($updated !== $content) {
            file_put_contents($file->getPathname(), $updated);
            echo "Updated backend validation in controller: " . $file->getFilename() . "\n";
        }
    }
}
