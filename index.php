<?php 
session_start();
require_once("vendor/autoload.php");

use \Slim\Slim;
use \Hcode\Page;
use \Hcode\PageAdmin;
use \Hcode\Model\User;
use \Hcode\Model\Category;

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

$app->get("/arbeitfirma/categories", function() {

	User::verifyLogin();

	$categories = Category::listAll();
    
	$page = new PageAdmin();

	$page->setTpl("categories", [
		'categories'=>$categories
	]);

});

$app->get("/arbeitfirma/categories/create", function() {

	User::verifyLogin();

	$page = new PageAdmin();

	$page->setTpl("categories-create");

});

$app->post("/arbeitfirma/categories/create", function() {

	User::verifyLogin();

	$category = new Category();

	$category->setData($_POST);

	$category->save();

    header("Location: /arbeitfirma/categories");

    exit;

});

$app->get("/arbeitfirma/categories/:idcategory/delete", function($idcategory) {

	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	$category->delete();

    header("Location: /arbeitfirma/categories");

    exit;

});

$app->get("/arbeitfirma/categories/:idcategory", function($idcategory) {

	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	$page = new PageAdmin();

	$page->setTpl("categories-update", [
		"category"=>$category->getValues()
	]);

});

$app->post("/arbeitfirma/categories/:idcategory", function($idcategory) {

	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	$category->setData($_POST);

	$category->save();

	header("Location: /arbeitfirma/categories");

    exit;

});

$app->run();

 ?>