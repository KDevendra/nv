<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$entry = \App\Models\PropertyEntry::latest()->first();

if (!$entry) {
    echo "No entries found.\n";
    exit;
}

echo "Entry Code: {$entry->code}\n";
echo "has_offices: " . ($entry->has_offices !== null ? ($entry->has_offices ? 'true' : 'false') : 'NULL') . "\n";
echo "no_of_offices: " . ($entry->no_of_offices ?? 'NULL') . "\n";
echo "office_sizes type: " . gettype($entry->office_sizes) . "\n";
echo "office_sizes raw DB: " . $entry->getRawOriginal('office_sizes') . "\n";
echo "office_sizes cast: " . json_encode($entry->office_sizes) . "\n";
