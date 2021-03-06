<?php

use \Hcode\PageAdminSis;
use \Hcode\Model\User;
use \Hcode\Model\Category;
use \Hcode\Model\Product;

$app->get("/arbeitfirma/categories", function() {

	User::verifyLogin();

	$categories = Category::listAll();
    
	$page = new PageAdminSis();

	$page->setTpl("categories", [
		'categories'=>$categories
	]);

});

$app->get("/arbeitfirma/categories/create", function() {

	User::verifyLogin();

	$page = new PageAdminSis();

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

	$page = new PageAdminSis();

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

$app->get("/arbeitfirma/categories/:idcategory/products", function($idcategory) {

	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	$page = new PageAdminSis;

	$page->setTpl("categories-products", [
		'category'=>$category->getValues(),
		'productsRelated'=>$category->getProducts(),
		'productsNotRelated'=>$category->getProducts(false)
	]);

});

$app->get("/arbeitfirma/categories/:idcategory/products/:idproduct/add", function($idcategory, $idproduct) {

	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	$product = new Product();

	$product->get((int)$idproduct);

	$category->addProduct($product);

	header("Location: /arbeitfirma/categories/".$idcategory."/products");

	exit;

});

$app->get("/arbeitfirma/categories/:idcategory/products/:idproduct/remove", function($idcategory, $idproduct) {

	User::verifyLogin();

	$category = new Category();

	$category->get((int)$idcategory);

	$product = new Product();

	$product->get((int)$idproduct);

	$category->removeProduct($product);

	header("Location: /arbeitfirma/categories/".$idcategory."/products");

	exit;

});

?>