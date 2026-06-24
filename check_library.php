<?php
define('LARAVEL_START', microtime(true));
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->boot();

use App\Models\Library;

echo 'Total library: ' . Library::count() . PHP_EOL;
foreach (Library::all() as $l) {
    echo $l->jenis . ' | ' . $l->nama . PHP_EOL;
    if (!empty($l->solusi)) {
        echo '  SOLUSI: ' . substr($l->solusi, 0, 120) . PHP_EOL;
    }
    echo PHP_EOL;
}
