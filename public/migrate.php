<?php
/**
 * Web-based Migration Runner
 * HAPUS FILE INI SETELAH MIGRATION BERHASIL
 *
 * Akses: https://crm.rzdigitalcreative.my.id/migrate.php
 */

header('Content-Type: text/plain; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== RUNNING MIGRATIONS ===\n\n";

try {
    $exitCode = Artisan::call('migrate', ['--force' => true]);
    echo Artisan::output();

    if ($exitCode === 0) {
        echo "\n✅ Migration berhasil!\n";
    } else {
        echo "\n❌ Migration gagal (exit code: {$exitCode})\n";
    }
} catch (\Throwable $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n=== CLEAR & REBUILD CACHE ===\n\n";

try {
    Artisan::call('optimize:clear');
    echo Artisan::output();
    
    Artisan::call('config:cache');
    echo Artisan::output();
    
    Artisan::call('route:cache');
    echo Artisan::output();
    
    Artisan::call('view:cache');
    echo Artisan::output();
    
    echo "\n✅ Cache rebuilt!\n";
} catch (\Throwable $e) {
    echo "❌ Cache error: " . $e->getMessage() . "\n";
}

echo "\n⚠️  PENTING: HAPUS FILE migrate.php INI SETELAH SELESAI!\n";
