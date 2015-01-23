<?php

require_once './src/Autoloader.php';

Autoloader::registerPath(__DIR__ . '/src/');
spl_autoload_register(array('Autoloader', 'load'));