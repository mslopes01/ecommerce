<?php 
session_start();
require_once("vendor/autoload.php");

use \Slim\Slim;
use \Hcode\Page;
use \Hcode\PageAdmin;
use \Hcode\Model\User;

$app = new Slim();

$app->config('debug', true);

$app->get('/', function() {
    
	$page = new Page();

	$page->setTpl("index");

});

$app->get('/arbeitfirma', function() {

	User::verifyLogin();
    
	$page = new PageAdmin();

	$page->setTpl("index");

});

$app->get('/arbeitfirma/login', function() {
    
	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false

	]);

	$page->setTpl("login");

});

$app->post('/arbeitfirma/login', function() {
    
	User::login($_POST["login"], $_POST["password"]);

	header("Location: /arbeitfirma");

	exit;

});

$app->get('/arbeitfirma/logout', function() {
    
	User::logout();

	header("Location: /arbeitfirma/login");

	exit;

});

$app->post("/arbeitfirma/users/create", function () {

 	User::verifyLogin();

	$user = new User();

 	$_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;

 	$_POST['despassword'] = password_hash($_POST["despassword"], PASSWORD_DEFAULT, [

 		"cost"=>12

 	]);

 	$user->setData($_POST);

	$user->save();

	header("Location: /arbeitfirma/users");

 	exit;

});

$app->get('/arbeitfirma/users', function() {

	User::verifyLogin();

	$users = User::listAll();
    
	$page = new PageAdmin();

	$page->setTpl("users", array(
		"users"=>$users
	));

});

$app->get('/arbeitfirma/users/create', function() {

	User::verifyLogin();
    
	$page = new PageAdmin();

	$page->setTpl("users-create");

});

$app->get('/arbeitfirma/users/:iduser/delete', function($iduser) {

	User::verifyLogin();

	$user = new User();

	$user->get((int)$iduser);

	$user->delete();

	header("Location: /arbeitfirma/users");

	exit();

});

$app->get('/arbeitfirma/users/:iduser', function($iduser) {

	User::verifyLogin();

	$user = new User();

	$user->get((int)$iduser);
    
	$page = new PageAdmin();

	$page->setTpl("users-update", array(
		"user"=>$user->getValues()
	));

});



$app->post('/arbeitfirma/users/:iduser', function($iduser) {

	User::verifyLogin();

	$user = new User();

	$_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;

	$user->get((int)$iduser);
    
	$user->setData($_POST);

	$user->update();

	header("Location: /arbeitfirma/users");

 	exit;

});

$app->get("/arbeitfirma/forgot", function() {
    
	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false

	]);

	$page->setTpl("forgot");

});

$app->post("/arbeitfirma/forgot", function() {
    
	$_POST["email"];
	$user = User::getForgot($_POST["email"]);

	header("Location: /arbeitfirma/forgot/sent");

	exit;

});

$app->get("/arbeitfirma/forgot/sent", function() {
    
	$page = new PageAdmin([
		"header"=>false,
		"footer"=>false

	]);

	$page->setTpl("forgot-sent");

});

$app->run();

 ?>