<?php

/**
 * Diagnostic Script - HAPUS SETELAH SELESAI DEBUG
 * Akses via: https://crm.rzdigitalcreative.my.id/diagnose.php
 */

// Muat Laravel
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

echo "<h2>🔍 CRM Diagnostic</h2><pre>";

// 1. Cek koneksi database
echo "\n=== DATABASE ===\n";
try {
    $pdo = $app->make('db')->connection()->getPdo();
    echo "✅ Database connected: " . $pdo->getAttribute(PDO::ATTR_DRIVER_NAME) . "\n";
} catch (\Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
}

// 2. Cek tabel-tabel kritis
echo "\n=== TABLES ===\n";
$tables = ['users', 'leads', 'projects', 'payments', 'activity_logs', 'message_logs',
           'project_subscriptions', 'company_settings', 'maintenances'];
foreach ($tables as $table) {
    try {
        $count = $app->make('db')->table($table)->count();
        echo "✅ {$table}: {$count} rows\n";
    } catch (\Exception $e) {
        echo "❌ {$table}: " . $e->getMessage() . "\n";
    }
}

// 3. Cek pending migrations
echo "\n=== MIGRATIONS ===\n";
try {
    $migrator = $app->make('migrator');
    $migrator->setConnection(null);
    $ran = $migrator->getRepository()->getRan();
    $allFiles = $migrator->getMigrationFiles($app->databasePath('migrations'));
    $pending = array_diff(array_keys($allFiles), $ran);
    if (empty($pending)) {
        echo "✅ No pending migrations\n";
    } else {
        echo "⚠️ Pending migrations:\n";
        foreach ($pending as $m) {
            echo "   - {$m}\n";
        }
    }
} catch (\Exception $e) {
    echo "❌ Migration check error: " . $e->getMessage() . "\n";
}

// 4. Cek cache
echo "\n=== CACHE / CONFIG ===\n";
$cacheFiles = ['config.php', 'routes-v7.php', 'events.php'];
foreach ($cacheFiles as $f) {
    $path = $app->bootstrapPath("cache/{$f}");
    echo (file_exists($path) ? "✅" : "⚠️  missing") . " bootstrap/cache/{$f}\n";
}

// 5. Cek storage writable
echo "\n=== STORAGE ===\n";
$dirs = ['framework/views', 'framework/cache', 'framework/sessions', 'logs'];
foreach ($dirs as $d) {
    $path = $app->storagePath($d);
    echo (is_writable($path) ? "✅ writable" : "❌ NOT writable") . " storage/{$d}\n";
}

// 6. Cek error terakhir di log
echo "\n=== RECENT LOG ERRORS ===\n";
$logFile = $app->storagePath('logs/laravel.log');
if (file_exists($logFile)) {
    $size = round(filesize($logFile) / 1024 / 1024, 2);
    echo "Log file size: {$size} MB\n";
    $lines = file($logFile);
    $lastLines = array_slice($lines, -30);
    echo implode("", $lastLines);
} else {
    echo "No log file found.\n";
}

echo "</pre>";
