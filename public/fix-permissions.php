<?php
/**
 * Run this file ONCE on your live server to create upload directories with proper permissions.
 * URL: https://zendoindia.com/fix-permissions.php
 * DELETE THIS FILE AFTER RUNNING IT!
 */

$dirs = [
    __DIR__ . '/uploads',
    __DIR__ . '/uploads/properties',
    __DIR__ . '/uploads/blogs',
    __DIR__ . '/uploads/blogs/content',
    __DIR__ . '/uploads/property-page-sections',
    __DIR__ . '/uploads/video-tour',
    __DIR__ . '/uploads/hero/posters',
    __DIR__ . '/uploads/builders',
    __DIR__ . '/uploads/testimonials',
    __DIR__ . '/uploads/team-members',
];

echo "<h2>Creating upload directories...</h2><pre>";

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        if (mkdir($dir, 0775, true)) {
            echo "✅ Created: " . str_replace(__DIR__, '', $dir) . "\n";
        } else {
            echo "❌ FAILED to create: " . str_replace(__DIR__, '', $dir) . "\n";
        }
    } else {
        // Try to make it writable
        chmod($dir, 0775);
        echo "✓ Already exists: " . str_replace(__DIR__, '', $dir) . " (writable: " . (is_writable($dir) ? 'YES' : 'NO') . ")\n";
    }
}

echo "\n\n--- Upload Test ---\n";
$testFile = __DIR__ . '/uploads/test-write.txt';
if (file_put_contents($testFile, 'test')) {
    unlink($testFile);
    echo "✅ Write test PASSED - uploads folder is writable!\n";
} else {
    echo "❌ Write test FAILED - uploads folder is NOT writable!\n";
    echo "   Ask your hosting provider to make public/uploads writable by PHP.\n";
}

echo "\n--- PHP Upload Limits ---\n";
echo "upload_max_filesize: " . ini_get('upload_max_filesize') . "\n";
echo "post_max_size: " . ini_get('post_max_size') . "\n";
echo "max_execution_time: " . ini_get('max_execution_time') . "\n";
echo "memory_limit: " . ini_get('memory_limit') . "\n";

echo "</pre><p style='color:red;font-weight:bold;'>⚠️ DELETE THIS FILE after running it!</p>";
