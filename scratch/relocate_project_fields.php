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

        // Find the div block containing project_name
        $pStart = strpos($content, 'name="project_name"');
        if ($pStart === false) continue;
        $divStart = strrpos(substr($content, 0, $pStart), '<div>');
        $divEnd = strpos($content, '</div>', $pStart) + strlen('</div>');
        $projBlock = substr($content, $divStart, $divEnd - $divStart);

        // Find the div block containing builder_developer_name
        $bStart = strpos($content, 'name="builder_developer_name"');
        if ($bStart === false) continue;
        $bDivStart = strrpos(substr($content, 0, $bStart), '<div>');
        $bDivEnd = strpos($content, '</div>', $bStart) + strlen('</div>');
        $builderBlock = substr($content, $bDivStart, $bDivEnd - $bDivStart);

        // Remove both blocks from content
        $cleanContent = str_replace([$projBlock, $builderBlock], '', $content);

        // Find part_of_a_project_society div
        $partStart = strpos($cleanContent, 'name="part_of_a_project_society"');
        if ($partStart === false) continue;
        $partDivEnd = strpos($cleanContent, '</div>', $partStart) + strlen('</div>');

        // Insert projectBlock & builderBlock right after part_of_a_project_society div
        $newContent = substr_replace($cleanContent, "\n" . $projBlock . "\n" . $builderBlock, $partDivEnd, 0);

        file_put_contents($file->getPathname(), $newContent);
        echo "Relocated project_name & builder_developer_name to Section B2 in: " . $file->getFilename() . "\n";
    }
}
