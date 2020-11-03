<?php
require __DIR__ . '/../vendor/autoload.php';

use Source\Minify;


$minify = new Minify(Minify::JS);

$minify->addFolder('../assets/js/');

$minifed = $minify->minify(__DIR__ . "/../assets/output", "scripts.js");


echo "<pre> The file is in {$minifed} " . PHP_EOL;
print_r($minify->getAddedFiles());