<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use App\Models\PropertyEntry;

$fillable = (new PropertyEntry())->getFillable();
$dbColumns = Schema::getColumnListing('property_entries');

echo "=== CHECKING FILLABLE COLUMNS AGAINST LIVE DB SCHEMA ===\n\n";

$missingInDb = [];
foreach ($fillable as $col) {
    if (!in_array($col, $dbColumns)) {
        $missingInDb[] = $col;
    }
}

if (empty($missingInDb)) {
    echo "ALL " . count($fillable) . " fillable columns exist in property_entries table!\n";
} else {
    echo "FOUND " . count($missingInDb) . " FILLABLE COLUMNS MISSING IN DB TABLE:\n";
    foreach ($missingInDb as $col) {
        echo "  - $col\n";
    }
}
