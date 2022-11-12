<?php namespace Kevinallenbriggs\Gallery\seeds;

use Faker\Factory;

// load Faker autoloader
require_once __DIR__ . '/../vendor/autoload.php';

define('IMAGE_DIR', dirname(__DIR__) . '/src/public/images/');
define('IMAGE_COUNT', rand(18, 27));

$faker = Factory::create();
$existingFilenames = glob(IMAGE_DIR . '*');

foreach ($existingFilenames as $filename) {
    if (is_file($filename)) unlink($filename); // delete file
}

for ($i = 0; $i < IMAGE_COUNT; $i++) {
    $faker->image(
        IMAGE_DIR
    );
}