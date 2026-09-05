<?php
/**
 * Raw PHP Diagnostic v3
 * HAPUS SETELAH SELESAI DEBUG
 */

header('Content-Type: text/plain; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== RAW PHP DIAGNOSTIC v3 ===\n\n";

echo "PHP Version: " . phpversion() . "\n";

echo "\n=== ENV FILE ===\n";
$envFile = __DIR__ . '/../.env';
echo ".env exists: " . (file_exists($envFile) ? "YES" : "NO") . "\n";
if (file_exists($envFile)) {
    $envContent = file_get_contents($envFile);
    // Tampilkan key-key penting (sembunyikan value sensitif)
    $keys = ['APP_ENV', 'APP_DEBUG', 'APP_KEY', 'DB_CONNECTION', 'DB_HOST', 'DB_DATABASE', 'DB_USERNAME', 'LOG_CHANNEL', 'LOG_STACK'];
    foreach ($keys as $key) {
        if (preg_match('/^' . $key . '=(.*)$/m', $envContent, $m)) {
            $val = trim($m[1]);
            // Sembunyikan password & key
            if (in_array($key, ['APP_KEY', 'DB_PASSWORD'])) {
                $val = substr($val, 0, 10) . '***';
            }
            echo "  {$key}={$val}\n";
        } else {
            echo "  {$key}=NOT SET\n";
        }
    }
}

echo "\n=== BOOTSTRAP CACHE ===\n";
$cacheDir = __DIR__ . '/../bootstrap/cache';
$cacheFiles = ['config.php', 'routes-v7.php', 'events.php', 'packages.php', 'services.php'];
foreach ($cacheFiles as $f) {
    $fp = $cacheDir . '/' . $f;
    echo "  {$f}: " . (file_exists($fp) ? filesize($fp) . " bytes" : "not cached") . "\n";
}

// Cek error messages dari log (bukan stack trace, tapi pesan error)
echo "\n=== ERROR MESSAGES FROM LOG ===\n";
$logFile = __DIR__ . '/../storage/logs/laravel.log';
if (file_exists($logFile)) {
    $size = round(filesize($logFile) / 1024 / 1024, 2);
    echo "Log size: {$size} MB\n\n";
    // Ambil semua baris yang mengandung pesan error (bukan stack trace)
    $lines = file($logFile);
    $errorLines = [];
    foreach ($lines as $line) {
        if (preg_match('/\[\d{4}-\d{2}-\d{2}.*\] (production|local)\.(ERROR|CRITICAL)/', $line)) {
            $errorLines[] = $line;
        }
    }
    // Tampilkan 20 error terakhir
    $recentErrors = array_slice($errorLines, -20);
    if (empty($recentErrors)) {
        echo "No ERROR/CRITICAL entries found.\n";
    } else {
        foreach ($recentErrors as $e) {
            echo $e;
        }
    }
} else {
    echo "No laravel.log found\n";
}

// Coba bootstrap Laravel dengan full boot
echo "\n\n=== LARAVEL FULL BOOT TEST ===\n";
try {
    require __DIR__ . '/../vendor/autoload.php';
    echo "1. Autoload OK\n";

    $app = require_once __DIR__ . '/../bootstrap/app.php';
    echo "2. App created OK\n";

    // Boot the application fully
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $response = $kernel->handle(
        $request = Illuminate\Http\Request::capture()
    );
    echo "3. Full boot OK\n";

    // Cek DB via app
    $pdo = $app->make('db')->connection()->getPdo();
    echo "4. Database: " . $pdo->getAttribute(PDO::ATTR_DRIVER_NAME) . "\n";

    $tables = ['users', 'leads', 'projects', 'activity_logs', 'message_logs',
               'project_subscriptions', 'company_settings', 'maintenances'];
    foreach ($tables as $t) {
        try {
            $count = $app->make('db')->table($t)->count();
            echo "  OK {$t}: {$count} rows\n";
        } catch (\Exception $e) {
            echo "  FAIL {$t}: " . $e->getMessage() . "\n";
        }
    }
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n\n";
    // Tampilkan beberapa baris trace yang relevan saja
    $trace = explode("\n", $e->getTraceAsString());
    $relevant = array_slice($trace, 0, 15);
    echo "Trace (first 15):\n" . implode("\n", $relevant) . "\n";
}
