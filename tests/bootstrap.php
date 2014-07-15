<?php

$locations[] = __DIR__ . "/../vendor/autoload.php";
$locations[] = __DIR__ . "/../../../autoload.php";

foreach ($locations as $location) {
    if (is_file($location)) {
        $loader = require $location;
        $loader->addPsr4('AndyTruong\\Serializer\\TestCases\\', __DIR__ . '/serializer');
        $loader->addPsr4('AndyTruong\\Serializer\\Fixtures\\', __DIR__ . '/fixtures');
        $loader->addPsr4('AndyTruong\\Serializer\\', __DIR__ . '/../src');
        break;
    }
}
