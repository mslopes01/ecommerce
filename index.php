<?php 
session_start();
require_once("vendor/autoload.php");

use \Slim\Slim;


$app = new Slim();

require_once("site.php");

require_once("admin.php");

$app->config('debug', true);

$app->run();

 ?>