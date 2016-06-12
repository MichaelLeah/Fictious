<?php
require '../vendor/autoload.php';
require 'database.php';

$configuration = [
  'settings' => [
    'displayErrorDetails' => true,
  ],
];

$container = new \Slim\Container($configuration);

$app = new \Slim\App($container);

require 'routes.php';