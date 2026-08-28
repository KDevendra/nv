<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$p = App\Models\PropertyEntry::find(122);
if ($p) {
    echo "Property 122 Found:\n";
    echo "part_of_a_project_society col: " . var_export($p->part_of_a_project_society, true) . "\n";
    echo "fieldValue part_of_a_project_society: " . var_export($p->fieldValue('part_of_a_project_society'), true) . "\n";
    echo "project_name: " . var_export($p->project_name, true) . "\n";
    echo "project_rera_id: " . var_export($p->project_rera_id, true) . "\n";
    echo "builder_developer_name: " . var_export($p->builder_developer_name, true) . "\n";
    echo "raw_data: " . var_export($p->raw_data, true) . "\n";
} else {
    echo "Property 122 not found in DB\n";
}
