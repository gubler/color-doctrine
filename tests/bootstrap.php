<?php

error_reporting(\E_ALL | \E_STRICT);
date_default_timezone_set('UTC');

// Ensure that composer has installed all dependencies
if (!file_exists(dirname(__DIR__) . '/vendor/autoload.php')) {
    exit("Dependencies must be installed using composer:\n\nphp composer.phar install --dev\n\n"
        . "See http://getcomposer.org for help with installing composer\n");
}

// Include the Composer autoloader
$loader = include dirname(__FILE__, 2) . '/vendor/autoload.php';

$loader->add('Doctrine\Tests\DBAL', __DIR__ . '/../vendor/doctrine/dbal/tests');
$loader->addPsr4('Gubler\\Color\\Doctrine\\', __DIR__);
