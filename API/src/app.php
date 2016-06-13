<?php
require '../vendor/autoload.php';
require 'database.php';

$configuration = [
  'settings' => [
    'displayErrorDetails' => false,
  ],
];

$container = new \Slim\Container($configuration);

$app = new \Slim\App($container);

require 'routes.php';