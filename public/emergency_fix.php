<?php

/**
 * Laravel EMERGENCY Reset Tool (v3 - Standalone)
 * 
 * This file is intentionally minimal and DOES NOT load Laravel.
 * It is used to fix the "No hint path defined" error by deleting broken cache files.
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

$basePath = __DIR__ . '/../'; // Assuming this file is in 'public/'
$cachePath = $basePath . 'bootstrap/cache/';

// Check if we are in the root or public folder
if (!is_dir($cachePath)) {
    $basePath = __DIR__ . '/';
    $cachePath = $basePath . 'bootstrap/cache/';
}

$filesToDelete = [
    'config.php',
    'routes-v7.php',
    'services.php',
    'packages.php',
    'events.php'
];

echo "<html><body style='font-family:sans-serif; padding:50px; line-height:1.6;'>";
echo "<h1>Laravel Emergency Reset Tool</h1>";
echo "<p>Checking directory: <code>$cachePath</code></p>";

if (!is_dir($cachePath)) {
    echo "<p style='color:red;'><strong>Error:</strong> Could not find bootstrap/cache directory. Please make sure this file is in your Laravel root or public folder.</p>";
} else {
    $deletedCount = 0;
    echo "<ul>";
    foreach ($filesToDelete as $file) {
        $fullPath = $cachePath . $file;
        if (file_exists($fullPath)) {
            if (unlink($fullPath)) {
                echo "<li style='color:green;'>Deleted: $file</li>";
                $deletedCount++;
            } else {
                echo "<li style='color:red;'>Failed to delete: $file (Check permissions)</li>";
            }
        } else {
            echo "<li>Not found (Skipped): $file</li>";
        }
    }
    echo "</ul>";

    if ($deletedCount > 0) {
        echo "<div style='background:#d4edda; padding:20px; border-radius:5px;'>";
        echo "<strong>Success!</strong> $deletedCount cache files removed. <br>";
        echo "Try refreshing your main website now. If it works, you can then use the full deployment_helper.php.";
        echo "</div>";
    } else {
        echo "<p>No stale cache files were found to delete.</p>";
    }
}

echo "<hr><p style='color:red;'><strong>Security:</strong> Delete this file immediately after use!</p>";
echo "</body></html>";
