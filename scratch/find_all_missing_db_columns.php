<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use App\Models\PropertyEntry;

$dbColumns = Schema::getColumnListing('property_entries');
$fillable = (new PropertyEntry())->getFillable();

echo "=== DB COLUMNS IN property_entries TABLE ===\n";
echo "Total columns: " . count($dbColumns) . "\n\n";

// Scan all views for input names
$dir = __DIR__ . '/../resources/views/owner/properties';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

$inputNames = [];
foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());
        preg_match_all('/name=["\']([a-zA-Z0-9_]+)["\']/i', $content, $matches);
        foreach ($matches[1] as $name) {
            if (!in_array($name, ['_token', '_method', 'action', 'wizard_step', 'photos', 'property_type'])) {
                $inputNames[$name] = true;
            }
        }
    }
}
$inputNames = array_keys($inputNames);

echo "=== INPUT NAMES IN WIZARD FORMS ===\n";
echo "Total unique input names: " . count($inputNames) . "\n\n";

$missingFillable = array_diff($fillable, $dbColumns);
echo "=== FILLABLE KEYS MISSING IN DATABASE TABLE ===\n";
foreach ($missingFillable as $col) {
    echo "  - $col\n";
}

$missingFormInputsInFillable = array_diff($inputNames, $fillable);
echo "\n=== FORM INPUTS NOT IN FILLABLE (GO TO CUSTOM FIELDS) ===\n";
foreach ($missingFormInputsInFillable as $input) {
    echo "  - $input\n";
}

$missingFormInputsInDbAndFillable = array_intersect($missingFillable, $inputNames);
echo "\n=== CRITICAL: FORM INPUTS IN FILLABLE BUT MISSING IN DATABASE TABLE (THROWS 1054 SQL ERROR!) ===\n";
foreach ($missingFormInputsInDbAndFillable as $col) {
    echo "  - $col\n";
}
