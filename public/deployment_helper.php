<?php

/**
 * Laravel Shared Hosting Deployment Helper (EMERGENCY VERSION)
 *
 * This script helps you run common artisan commands and fix "No hint path" errors.
 *
 * USAGE:
 * 1. Upload this file to your website root.
 * 2. Visit yourdomain.com/deployment_helper.php
 * 3. IMPORTANT: DELETE THIS FILE AFTER USE!
 */

// --- EMERGENCY CACHE CLEAR (Runs without booting Laravel) ---
if (isset($_GET['emergency_clear'])) {
    // Try to find bootstrap/cache regardless of if we are in root or public
    $cachePath = file_exists(__DIR__ . '/bootstrap/cache/')
        ? __DIR__ . '/bootstrap/cache/'
        : __DIR__ . '/../bootstrap/cache/';

    $files = ['config.php', 'routes-v7.php', 'services.php', 'packages.php', 'events.php'];
    $deleted = [];

    foreach ($files as $file) {
        if (file_exists($cachePath . $file)) {
            if (unlink($cachePath . $file)) {
                $deleted[] = $file;
            }
        }
    }

    echo "<div style='background: #d4edda; color: #155724; padding: 15px; margin-bottom: 20px; border-radius: 5px;'>";
    echo "<strong>Emergency Reset Successful!</strong><br>";
    if (empty($deleted)) {
        echo "No stale cache files found.";
    } else {
        echo "Deleted: " . implode(', ', $deleted);
    }
    echo "<br><a href='deployment_helper.php'>Click here to return to helper</a>";
    echo "</div>";
}

function runCommand($command) {
    echo "<h3>Running: php artisan $command</h3>";
    try {
        $vendorPath = file_exists(__DIR__ . '/vendor/autoload.php')
            ? __DIR__ . '/vendor/autoload.php'
            : __DIR__ . '/../vendor/autoload.php';

        $bootstrapPath = file_exists(__DIR__ . '/bootstrap/app.php')
            ? __DIR__ . '/bootstrap/app.php'
            : __DIR__ . '/../bootstrap/app.php';

        if (!file_exists($vendorPath)) {
            throw new Exception("vendor/autoload.php not found. Did you run composer install?");
        }

        require $vendorPath;
        $app = require $bootstrapPath;

        // If require returned true instead of the app instance,
        // it means the file was already included. We need to find the instance.
        if ($app === true) {
            if (isset($GLOBALS['app'])) {
                $app = $GLOBALS['app'];
            } elseif (function_exists('app')) {
                $app = app();
            }
        }

        if (!($app instanceof \Illuminate\Foundation\Application)) {
            throw new Exception("Could not initialize Laravel Application instance.");
        }

        // Boot the kernel to register providers
        $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
        $status = $kernel->call($command);

        echo "<pre style='background: #eee; padding: 10px; border: 1px solid #ccc;'>";
        echo $kernel->output();
        echo "</pre>";
        echo "<p>Status: " . ($status === 0 ? 'Success' : 'Failed') . "</p>";
    } catch (Exception $e) {
        echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
        echo "<pre style='font-size: 11px; color: #666; background: #fff; padding: 10px;'>" . $e->getTraceAsString() . "</pre>";
    }
    echo "<hr>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Laravel Deployment Helper</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; padding: 40px; line-height: 1.6; background: #f4f7f6; color: #333; }
        .container { max-width: 900px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #2c3e50; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        .btn { display: inline-block; padding: 12px 24px; background: #3490dc; color: #fff; text-decoration: none; border-radius: 5px; margin: 5px 0; font-weight: bold; border: none; cursor: pointer; }
        .btn-danger { background: #e3342f; }
        .btn-warning { background: #ffc107; color: #212529; }
        .btn-warning:hover { background: #e0a800; }
        .warning-box { background: #fff3cd; color: #856404; padding: 15px; border: 1px solid #ffeeba; border-radius: 5px; margin-bottom: 20px; }
        .card { border: 1px solid #e3e8ee; padding: 20px; border-radius: 6px; margin-bottom: 20px; }
        .alert { padding: 15px; border-radius: 4px; margin-bottom: 20px; }
        .alert-warning { background: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
    </style>
</head>
<body>
    <div class="container">
        <h1>HMS Deployment Helper</h1>

        <div class="warning-box">
            <strong>⚠️ Server Compatibility Note:</strong><br>
            Your shared hosting environment is incompatible with Laravel's <code>route:cache</code>.
            <strong>Do NOT use the "Optimize" button</strong> if your home page shows "Method Not Allowed".
            Instead, use <strong>"Nuclear Clear"</strong> to keep the app in stable "Live Mode".
        </div>

        <div class="alert alert-warning">
            <strong>Critical Fix:</strong> If you see "No hint path defined", click the Emergency button below first.
        </div>

        <div class="card">
            <h3>Step 1: Emergency Reset</h3>
            <p>This deletes cached files from your local machine that are breaking the server.</p>
            <a href="?emergency_clear=1" class="btn btn-danger">RUN EMERGENCY CACHE CLEAR</a>
        </div>

        <div class="card">
            <h3>Step 2: Standard Operations</h3>
            <p>Once the error is gone, use these to set up your app.</p>
            <a href="?cmd=migrate" class="btn">Run Migrations</a>
            <a href="?cmd=migrate-fresh" class="btn btn-warning" style="background:#fd7e14;">Fresh Migrate & Seed</a>
            <a href="?cmd=db-export" class="btn btn-warning" style="background:#20c997;">Export DB for Server</a>
            <a href="?cmd=storage-link" class="btn">Create Storage Link</a>
            <a href="?cmd=optimize" class="btn btn-warning">Optimize (May break Home Page)</a>
            <a href="?cmd=clear-all" class="btn btn-danger" style="background:#dc3545;">Nuclear Clear (Stable Mode)</a>
            <a href="?cmd=check-env" class="btn btn-warning" style="background:#6c757d;">Check Environment Info</a>
        </div>

        <?php
        if (isset($_GET['cmd'])) {
            $cmd = $_GET['cmd'];
            switch ($cmd) {
                case 'migrate': runCommand('migrate --force'); break;
                case 'migrate-fresh': runCommand('migrate:fresh --seed --force'); break;
                case 'db-export':
                    echo "<h3>Exporting Database...</h3>";
                    try {
                        $rootPath = file_exists(__DIR__ . '/bootstrap/app.php') ? __DIR__ : __DIR__ . '/..';
                        require_once $rootPath . '/vendor/autoload.php';
                        $app = require $rootPath . '/bootstrap/app.php';
                        $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

                        $tables = \Illuminate\Support\Facades\DB::select('SHOW TABLES');
                        $dbName = \Illuminate\Support\Facades\DB::getDatabaseName();
                        $tableNameKey = "Tables_in_" . $dbName;
                        $sql = "-- HMS Database Dump\n";
                        $sql .= "-- Generated: " . date('Y-m-d H:i:s') . "\n\n";
                        $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

                        foreach ($tables as $table) {
                            $tableName = $table->$tableNameKey;

                            // Structure
                            $res = \Illuminate\Support\Facades\DB::select("SHOW CREATE TABLE `$tableName`")[0];
                            $createSql = $res->{'Create Table'};
                            $sql .= "DROP TABLE IF EXISTS `$tableName`;\n";
                            $sql .= $createSql . ";\n\n";

                            // Data
                            $rows = \Illuminate\Support\Facades\DB::table($tableName)->get();
                            if ($rows->count() > 0) {
                                $sql .= "INSERT INTO `$tableName` VALUES \n";
                                $rowStrings = [];
                                foreach ($rows as $row) {
                                    $values = array_values((array)$row);
                                    $values = array_map(function($v) {
                                        if (is_null($v)) return 'NULL';
                                        return "'" . addslashes($v) . "'";
                                    }, $values);
                                    $rowStrings[] = "(" . implode(', ', $values) . ")";
                                }
                                $sql .= implode(",\n", $rowStrings) . ";\n\n";
                            }
                        }
                        $sql .= "SET FOREIGN_KEY_CHECKS=1;";

                        $fileName = 'db_dump_' . date('Y_m_d_His') . '.sql';
                        file_put_contents($rootPath . '/public/' . $fileName, $sql);
                        echo "<div class='alert' style='background:#d4edda; color:#155724; border:1px solid #c3e6cb;'>";
                        echo "<strong>Success!</strong> Database exported to: <strong>public/$fileName</strong><br>";
                        echo "You can now download this file and import it to your server.";
                        echo "</div>";
                    } catch (\Exception $e) {
                        echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
                    }
                    break;
                case 'storage-link':
                    echo "<h3>Creating Storage Link...</h3>";
                    $rootPath = file_exists(__DIR__ . '/bootstrap/app.php') ? __DIR__ : __DIR__ . '/..';
                    $target = $rootPath . '/storage/app/public';
                    $link = $rootPath . '/public/storage';

                    if (file_exists($link)) {
                        echo "<p style='color: orange;'>Storage link already exists or directory 'public/storage' is not empty.</p>";
                    } else {
                        // Attempt to create symlink using pure PHP
                        if (function_exists('symlink')) {
                            if (@symlink($target, $link)) {
                                echo "<p style='color: green;'>Success: Storage symlink created via PHP symlink().</p>";
                            } else {
                                echo "<p style='color: red;'>Error: PHP symlink() failed. Your hosting might have restricted it.</p>";
                                echo "<p>Target: $target</p>";
                                echo "<p>Link: $link</p>";
                            }
                        } else {
                            echo "<p style='color: red;'>Error: symlink() function is disabled on your hosting.</p>";
                        }
                    }
                    break;
                case 'optimize': runCommand('optimize'); break;
                case 'clear-all':
                    echo "<h3>Nuclear Clear in Progress...</h3>";
                    // Clear all caches via artisan
                    runCommand('optimize:clear');
                    runCommand('route:clear');
                    runCommand('config:clear');
                    runCommand('view:clear');
                    runCommand('cache:clear');

                    // Manual deletion of cache files
                    $rootPath = file_exists(__DIR__ . '/bootstrap/app.php') ? __DIR__ : __DIR__ . '/..';
                    $cachePath = $rootPath . '/bootstrap/cache/';
                    $files = ['config.php', 'routes-v7.php', 'services.php', 'packages.php', 'events.php'];
                    foreach ($files as $file) {
                        if (file_exists($cachePath . $file)) {
                            @unlink($cachePath . $file);
                            echo "<p>Manually deleted: $file</p>";
                        }
                    }

                    // NEW: Also clear the application cache (not just framework cache)
                    try {
                        require_once $rootPath . '/vendor/autoload.php';
                        $app = require $rootPath . '/bootstrap/app.php';
                        if ($app instanceof \Illuminate\Foundation\Application) {
                            $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
                            \Illuminate\Support\Facades\Cache::flush();
                            echo "<p>Application Cache Flushed.</p>";
                        }
                    } catch (\Exception $e) {
                        echo "<p>Note: Could not flush App Cache (Normal): " . $e->getMessage() . "</p>";
                    }

                    echo "<p style='color:green;'>All caches cleared successfully. App is now in 'Live Mode' (no cache).</p>";
                    break;
                case 'check-env':
                    echo "<h3>Environment Diagnostics</h3>";
                    echo "<ul>";
                    echo "<li><strong>PHP Version:</strong> " . PHP_VERSION . "</li>";
                    echo "<li><strong>Disabled Functions:</strong> " . (ini_get('disable_functions') ?: 'None') . "</li>";
                    echo "<li><strong>Symlink Available:</strong> " . (function_exists('symlink') ? 'Yes' : 'No') . "</li>";
                    echo "<li><strong>Safe Mode:</strong> " . (ini_get('safe_mode') ? 'On' : 'Off') . "</li>";
                    echo "<li><strong>Memory Limit:</strong> " . ini_get('memory_limit') . "</li>";
                    echo "<li><strong>Current Directory:</strong> " . __DIR__ . "</li>";
                    echo "</ul>";
                    break;
            }
        }
        ?>

        <div style="margin-top: 40px; padding: 20px; background: #f8d7da; border-radius: 6px; color: #721c24;">
            <strong>SECURITY:</strong> Delete this file (<code>deployment_helper.php</code>) immediately after your app is working.
        </div>
    </div>
</body>
</html>
