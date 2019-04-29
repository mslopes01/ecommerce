<?php

use \Hcode\PageAdminSis;
use \Hcode\Model\User;

$app->get('/arbeitfirma', function() {

	User::verifyLogin();
    
	$page = new PageAdminSis();

	$page->setTpl("index");

});

$app->get('/arbeitfirma/login', function() {
    
	$page = new PageAdminSis([
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

?>