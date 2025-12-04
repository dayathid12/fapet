<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

$controller = new App\Http\Controllers\PeminjamanKendaraanController();
$response = $controller->show();
$html = $response->render();

// Save to file to inspect
file_put_contents('form_debug.html', $html);

// Check for form-group
$count = substr_count($html, 'form-group');
echo "Form-group count: $count\n";

// Check for input fields
$inputCount = substr_count($html, '<input');
echo "Input count: $inputCount\n";

// Check for select fields
$selectCount = substr_count($html, '<select');
echo "Select count: $selectCount\n";

// Check for textarea
$textareaCount = substr_count($html, '<textarea');
echo "Textarea count: $textareaCount\n";

// Check for step-content
$stepCount = substr_count($html, 'step-content');
echo "Step-content count: $stepCount\n";

// Find first form-group
$pos = strpos($html, 'form-group');
if ($pos !== false) {
    echo "\nFirst form-group section:\n";
    echo substr($html, $pos, 300) . "\n";
}

echo "\nForm HTML saved to form_debug.html\n";
?>
