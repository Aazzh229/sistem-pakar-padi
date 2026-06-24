<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== LIBRARY TABLE DATA ===" . PHP_EOL;
$libs = \App\Models\Library::all();
foreach ($libs as $l) {
    $hasSolusi = strlen($l->solusi ?? '') > 0 ? 'YES' : 'NO';
    echo "ID:{$l->id} | {$l->jenis} | {$l->nama} | solusi:{$hasSolusi}" . PHP_EOL;
}

echo PHP_EOL . "=== CHECK deteksi.start ROUTE ===" . PHP_EOL;
$routes = app('router')->getRoutes();
foreach ($routes as $route) {
    if (str_contains($route->uri(), 'deteksi')) {
        echo $route->methods()[0] . " " . $route->uri() . " -> " . $route->getActionName() . PHP_EOL;
    }
}
