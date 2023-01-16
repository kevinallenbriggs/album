<?php

use Symfony\Component\Dotenv\Dotenv;

$rootDir = dirname(__DIR__, 2);

require "$rootDir/vendor/autoload.php";

$dotenv = new Dotenv();
$dotenv->load("$rootDir/.env");

$validMimeTypes = [
    // 'image/png',
    'image/jpeg',
    // 'image/webp',
];


$outputDir = $_ENV['THUMBNAIL_OUTPUT_DIR'];
$thumbnailWidth = $_ENV['THUMBNAIL_WIDTH'];

foreach (glob("$rootDir/var/images/*") as $imgPath) {
    if (! in_array(mime_content_type($imgPath), $validMimeTypes)) continue;

    create_thumbnail($imgPath, $outputDir, $thumbnailWidth);
}

function create_thumbnail($inputPath, $outputPath, $width) {

    $commandTemplate = <<<EOL
mogrify \
    -path %s \
    -unsharp 0.25x0.25+8+0.065 \
    -thumbnail x%d \
    -dither None \
    -posterize 136 \
    -quality 82 \
    -define jpeg:fancy-upsampling=off \
    -define png:compression-level=9 \
    -define png:compression-strategy=1 \
    -define png:exclude-chunk=all \
    -interlace none \
    -colorspace sRGB \
    -strip \
    -auto-orient \
    %s
EOL;

    $command = sprintf(
        $commandTemplate,
        $outputPath,
        $width,
        $inputPath
    );

    exec($command);
}