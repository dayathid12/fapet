<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
$request = \Illuminate\Http\Request::capture();
$response = $kernel->handle($request);

// Test the controller
$controller = new App\Http\Controllers\PeminjamanKendaraanController();
$viewResponse = $controller->show();
$html = $viewResponse->render();

echo "View rendered: " . strlen($html) . " bytes\n";
echo "Has wilayah data: " . (strpos($html, 'option') !== false ? "YES" : "NO") . "\n";
echo "Has Bandung: " . (strpos($html, 'Bandung') !== false ? "YES" : "NO") . "\n";
echo "First 500 chars:\n";
echo substr($html, 0, 500) . "\n";
?>
