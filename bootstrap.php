<?php
use Insphare\Base\Autoloader;
use Insphare\Base\ObjectContainer;
use Insphare\Config\Configuration;

$path = __DIR__ . '/lib/insphare';
$autoloader = new Autoloader();
$autoloader->addIncludePath($path);
$autoloader->setNameSpace('Insphare');
$autoloader->register();

$container = new ObjectContainer();
$container->setConfiguration(new Configuration());
