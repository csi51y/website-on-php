<?php

/**
 * Контроллер страницы категории (/category/1)
 */

// подключаем модели
include_once '../models/CategoriesModel.php';
include_once '../models/ProductsModel.php';

// формирование страницы категорий
function indexAction($smarty){
	$catId = isset($_GET['id']) ? intval($_GET['id']) : null;
	if($catId === null) exit();

	$rsChildCats = null;
	$rsProducts = null;
	$rsCategory = getCatById($catId);

	// Если главная категория, то показываем дочерние категории, иначе показываем товар
	if($rsCategory['parent_id'] == 0){
		$rsChildCats = getChildrenForCat($catId);
	} else {
		$rsProducts = getProductsByCat($catId);
	}
	$rsCategories = getAllMainCatsWithChildren();

	$smarty->assign('pageTitle', 'Товары категории ' . $rsCategory['name']);
	$smarty->assign('rsCategory', $rsCategory);
	$smarty->assign('rsChildCats', $rsChildCats);
	$smarty->assign('rsProducts', $rsProducts);
	$smarty->assign('rsCategories', $rsCategories);
	// Передача $_GET['id'] в смарти для проверки наличия товара
	$smarty->assign('catId', $catId);

	loadTemplate($smarty, 'header');
	loadTemplate($smarty, 'category');
	loadTemplate($smarty, 'footer');
}