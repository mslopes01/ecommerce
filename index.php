<?php 
session_start();
require_once("vendor/autoload.php");

use \Slim\Slim;


$app = new Slim();

require_once("site.php");
require_once("arbeitfirma.php");
require_once("arbeitfirma-categories.php");
require_once("arbeitfirma-products.php");
require_once("arbeitfirma-users.php");

$app->config('debug', true);

$app->run();

 ?>