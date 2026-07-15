<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Test the fix on existing entries
$entries = \App\Models\PropertyEntry::whereNotNull('office_sizes')->get();

echo "Found " . $entries->count() . " entries with office_sizes data\n\n";

foreach ($entries as $entry) {
    echo "Entry {$entry->code}:\n";
    echo "  Raw DB value: " . $entry->getRawOriginal('office_sizes') . "\n";
    
    // Check if it's double-encoded (string of a JSON string)
    $raw = $entry->getRawOriginal('office_sizes');
    if ($raw && is_string($raw) && $raw !== 'null') {
        $decoded = json_decode($raw, true);
        
        if (is_string($decoded)) {
            echo "  ❌ ISSUE: Double-encoded as string\n";
            echo "  Fixing...\n";
            
            // Fix it by decoding twice
            $actualData = json_decode($decoded, true);
            $entry->office_sizes = $actualData ?: [];
            $entry->save();
            
            echo "  ✅ Fixed! Now stored as: " . json_encode($entry->office_sizes) . "\n";
        } else {
            echo "  ✅ Already correct format\n";
            echo "  Current value: " . json_encode($decoded) . "\n";
        }
    }
    echo "\n";
}

echo "Done!\n";
