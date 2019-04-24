<?php

use \Hcode\PageAdmin;
use \Hcode\Model\User;
use \Hcode\Model\Products;

$app->get("/arbeitfirma/products", function() {

	User::verifyLogin();

	$products = Products::listAll();
    
	$page = new PageAdmin();

	$page->setTpl("products", [
		'products'=>$products
	]);

});

$app->get("/arbeitfirma/products/create", function() {

	User::verifyLogin();

	$page = new PageAdmin();

	$page->setTpl("products-create");

});

$app->post("/arbeitfirma/products/create", function() {

	User::verifyLogin();

	$Products = new Products();

	$Products->setData($_POST);

	$Products->save();

    header("Location: /arbeitfirma/products");

    exit;

});

$app->get("/arbeitfirma/products/:idProducts/delete", function($idProducts) {

	User::verifyLogin();

	$Products = new Products();

	$Products->get((int)$idProducts);

	$Products->delete();

    header("Location: /arbeitfirma/products");

    exit;

});

$app->get("/arbeitfirma/products/:idProducts", function($idProducts) {

	User::verifyLogin();

	$Products = new Products();

	$Products->get((int)$idProducts);

	$page = new PageAdmin();

	$page->setTpl("products-update", [
		"Products"=>$Products->getValues()
	]);

});

$app->post("/arbeitfirma/products/:idProducts", function($idProducts) {

	User::verifyLogin();

	$Products = new Products();

	$Products->get((int)$idProducts);

	$Products->setData($_POST);

	$Products->save();

	header("Location: /arbeitfirma/products");

    exit;

});

?>