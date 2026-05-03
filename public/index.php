<?php
session_start();
define('BASE_PATH', dirname(__DIR__));

require BASE_PATH . '/config/database.php';
require BASE_PATH . '/core/Database.php';
require BASE_PATH . '/core/Model.php';
require BASE_PATH . '/core/Controller.php';
require BASE_PATH . '/core/Router.php';

// Autoload models
foreach (glob(BASE_PATH . '/app/models/*.php') as $model) require $model;

$router = new Router();
require BASE_PATH . '/routes/web.php';
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
