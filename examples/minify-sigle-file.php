<?php
require __DIR__ . '/../vendor/autoload.php';

use Source\Minify;

$minify = new Minify(Minify::JS);

$minify->addFile(__DIR__ . '/../assets/js/jquery.js');
$minify->addFile(__DIR__ . '/../assets/js/flatpick.js');
$minify->addFile(__DIR__ . '/../assets/js/flatpick-pt.js');

$minifed = $minify->minify(__DIR__ . "/../assets/output", "scripts.js");


echo "<pre> The file is in {$minifed} " . PHP_EOL;
print_r($minify->getAddedFiles());