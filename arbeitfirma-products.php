<?php

use \Hcode\PageAdmin;
use \Hcode\Model\User;
use \Hcode\Model\Product;

$app->get("/arbeitfirma/products", function() {

	User::verifyLogin();

	$product = Product::listAll();
    
	$page = new PageAdmin();

	$page->setTpl("products", [
		'products'=>$product
	]);

});

$app->get("/arbeitfirma/products/create", function() {

	User::verifyLogin();

	$page = new PageAdmin();

	$page->setTpl("products-create");

});

$app->post("/arbeitfirma/products/create", function() {

	User::verifyLogin();

	$product = new Product();

	$product->setData($_POST);

	$product->save();

    header("Location: /arbeitfirma/products");

    exit;

});

$app->get("/arbeitfirma/products/:idProducts/delete", function($idProducts) {

	User::verifyLogin();

	$product = new Product();

	$product->get((int)$idProducts);

	$product->delete();

    header("Location: /arbeitfirma/products");

    exit;

});

$app->get("/arbeitfirma/products/:idProducts", function($idproduct) {

	User::verifyLogin();

	$product = new Product();

	$product->get((int)$idproduct);

	$page = new PageAdmin();

	$page->setTpl("products-update", [
		"product"=>$product->getValues()
	]);

});

$app->post("/arbeitfirma/products/:idProducts", function($idProducts) {

	User::verifyLogin();

	$product = new Product();

	$product->get((int)$idProducts);

	$product->setData($_POST);

	$product->save();

	$product->setPhoto($_FILES["file"]);

	header("Location: /arbeitfirma/products");

    exit;

});

?>