<?php
$content = file_get_contents('resources/views/owner/properties/builder-floor/create.blade.php');

$projPattern = '/<div>\s*<label[^>]*>.*?Project\s+Name.*?<\/label>\s*<input[^>]*name="project_name"[^>]*>\s*<\/div>/is';
$builderPattern = '/<div>\s*<label[^>]*>.*?Builder\s*\/\s*Developer\s+Name.*?<\/label>\s*<input[^>]*name="builder_developer_name"[^>]*>\s*<\/div>/is';

echo "Proj match: " . preg_match($projPattern, $content, $m1) . "\n";
if (!empty($m1)) echo "M1: " . $m1[0] . "\n";

echo "Builder match: " . preg_match($builderPattern, $content, $m2) . "\n";
if (!empty($m2)) echo "M2: " . $m2[0] . "\n";
