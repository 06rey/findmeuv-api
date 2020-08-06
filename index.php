<?php

require_once __DIR__.'/vendor/autoload.php';

define('DS', DIRECTORY_SEPARATOR);
define('APP_PATH', __DIR__);

$app = new System\Application;
$response = $app->handleRequest();
$response->send();