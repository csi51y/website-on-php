<?php
/**
 * Контроллер страницы выбранного товара (/protuct/1)
 */

 // подключаем модели
include_once '../models/ProductsModel.php';
include_once '../models/CategoriesModel.php';

// формирование страницы продукта
function indexAction($smarty){
	$itemId = isset($_GET['id']) ? intval($_GET['id']) : null;
	if ($itemId === null) exit();

	// получить данные товара
	$rsProduct = getProductById($itemId);

	// получить все категории
	$rsCategories = getAllMainCatsWithChildren();

	// инициализации переменной-флага
	$smarty->assign('itemInCart', 0);
	if (in_array($itemId, $_SESSION['cart'])){
		$smarty->assign('itemInCart', 1);
	}

	$smarty->assign('pageTitle', 'Купить ' . $rsProduct['name']);
	$smarty->assign('rsCategories', $rsCategories);
	$smarty->assign('rsProduct', $rsProduct);

	loadTemplate($smarty, 'header');
	loadTemplate($smarty, 'product');
	loadTemplate($smarty, 'footer');
}