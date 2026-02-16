<?php

use App\Models\Documentary;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Purging mock documentaries...\n";

try {
    // We only want to delete if items were likely from the seeder
    // For safety in this environment, we truncate since it's a dev cleanup
    DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    Documentary::truncate();
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    
    echo "SUCCESS: All documentary data purged.\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
