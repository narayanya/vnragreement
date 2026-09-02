<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Find vertical-related tables
$tables = DB::select("SHOW TABLES");
$key = array_key_first((array) $tables[0]);
foreach ($tables as $t) {
    $name = $t->$key;
    if (stripos($name, 'vert') !== false || stripos($name, 'core') !== false) {
        echo $name . "\n";
    }
}
