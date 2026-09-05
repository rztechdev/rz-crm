<?php
/**
 * Raw PHP Diagnostic - tanpa Laravel
 * HAPUS SETELAH SELESAI DEBUG
 */

header('Content-Type: text/plain; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== RAW PHP DIAGNOSTIC ===\n\n";

// 1. PHP Version
echo "PHP Version: " . phpversion() . "\n";

// 2. Cek .env ada
echo "\n=== ENV FILE ===\n";
$envFile = __DIR__ . '/../.env';
$envProdFile = __DIR__ . '/../.env.production';
echo ".env exists: " . (file_exists($envFile) ? "YES" : "NO") . "\n";
echo ".env.production exists: " . (file_exists($envProdFile) ? "YES" : "NO") . "\n";

// 3. Cek vendor/autoload.php ada
echo "\n=== VENDOR ===\n";
$autoload = __DIR__ . '/../vendor/autoload.php';
echo "vendor/autoload.php exists: " . (file_exists($autoload) ? "YES" : "NO") . "\n";

// 4. Cek storage writable
echo "\n=== STORAGE ===\n";
$storageDirs = [
    'storage/logs',
    'storage/framework/views',
    'storage/framework/cache',
    'storage/framework/sessions',
];
foreach ($storageDirs as $dir) {
    $path = __DIR__ . '/../' . $dir;
    $exists = is_dir($path);
    $writable = $exists ? is_writable($path) : false;
    echo "{$dir}: " . ($exists ? "exists" : "MISSING") . " | " . ($writable ? "writable" : "NOT writable") . "\n";
}

// 5. Cek bootstrap/cache writable
echo "\n=== BOOTSTRAP CACHE ===\n";
$cacheDir = __DIR__ . '/../bootstrap/cache';
echo "bootstrap/cache writable: " . (is_writable($cacheDir) ? "YES" : "NO") . "\n";
$cacheFiles = ['config.php', 'routes-v7.php', 'events.php', 'packages.php', 'services.php'];
foreach ($cacheFiles as $f) {
    $fp = $cacheDir . '/' . $f;
    echo "  {$f}: " . (file_exists($fp) ? filesize($fp) . " bytes" : "not cached") . "\n";
}

// 6. Cek error log terakhir
echo "\n=== RECENT LOG (last 50 lines) ===\n";
$logFile = __DIR__ . '/../storage/logs/laravel.log';
if (file_exists($logFile)) {
    $size = round(filesize($logFile) / 1024 / 1024, 2);
    echo "Log size: {$size} MB\n\n";
    $lines = file($logFile);
    $lastLines = array_slice($lines, -50);
    echo implode("", $lastLines);
} else {
    echo "No laravel.log found\n";
    // Cek daily logs
    $logDir = __DIR__ . '/../storage/logs/';
    $files = glob($logDir . 'laravel-*.log');
    if (!empty($files)) {
        $latest = end($files);
        echo "Found daily log: " . basename($latest) . "\n";
        $lines = file($latest);
        $lastLines = array_slice($lines, -50);
        echo implode("", $lastLines);
    } else {
        echo "No log files found at all.\n";
    }
}

// 7. Coba load Laravel dan tangkap error
echo "\n\n=== LARAVEL BOOTSTRAP TEST ===\n";
try {
    require $autoload;
    echo "✅ Autoload OK\n";
    
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    echo "✅ App bootstrap OK\n";
    
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    echo "✅ Kernel OK\n";
    
    // Cek DB
    $pdo = $app->make('db')->connection()->getPdo();
    echo "✅ Database connected: " . $pdo->getAttribute(PDO::ATTR_DRIVER_NAME) . "\n";
    
    // Cek tabel kritis
    $tables = ['users', 'leads', 'projects', 'activity_logs', 'message_logs', 
               'project_subscriptions', 'company_settings', 'maintenances'];
    foreach ($tables as $t) {
        try {
            $count = $app->make('db')->table($t)->count();
            echo "  ✅ {$t}: {$count} rows\n";
        } catch (\Exception $e) {
            echo "  ❌ {$t}: " . $e->getMessage() . "\n";
        }
    }
} catch (\Throwable $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}
