<?php
require __DIR__ . '/../vendor/autoload.php';

$dir = __DIR__ . '/../resources/views/owner/properties';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php' && $file->getFilename() === 'create.blade.php') {
        $content = file_get_contents($file->getPathname());

        if (!str_contains($content, 'name="project_name"') || !str_contains($content, 'name="part_of_a_project_society"')) {
            continue;
        }

        $projPattern = '/<div>\s*<label[^>]*>\s*Project\s+Name\s*<\/label>.*?name="project_name".*?<\/div>/is';
        $builderPattern = '/<div>\s*<label[^>]*>\s*Builder\s*\/\s*Developer\s+Name\s*<\/label>.*?name="builder_developer_name".*?<\/div>/is';

        $hasProj = preg_match($projPattern, $content, $projMatches);
        $hasBuilder = preg_match($builderPattern, $content, $builderMatches);

        if ($hasProj && $hasBuilder) {
            $projHtml = $projMatches[0];
            $builderHtml = $builderMatches[0];

            $updated = preg_replace($projPattern, '', $content);
            $updated = preg_replace($builderPattern, '', $updated);

            $partOfProjectPattern = '/(<div>\s*<label[^>]*>[^<]*Part\s+of\s+a\s+Project\s*\/\s*Society[^<]*<\/label>.*?<\/select>\s*<\/div>)/is';

            $inserted = "$1\n                            " . trim($projHtml) . "\n                            " . trim($builderHtml);

            $updated = preg_replace($partOfProjectPattern, $inserted, $updated, 1);

            if ($updated && $updated !== $content) {
                file_put_contents($file->getPathname(), $updated);
                echo "Successfully moved project_name & builder_developer_name to Section B2 in: " . $file->getPathname() . "\n";
            }
        }
    }
}
