<?php
/**
 * Контроллер работы с корзиной (/cart/)
 */

// подключаем модели
include_once '../models/CategoriesModel.php';
include_once '../models/ProductsModel.php';

/** Добавление продукта в корзину
 * @return json информация об операции (успех, кол-во элементов в корзине)
 */
function addtocartAction($itemId){
	$itemId = isset($_GET['id']) ? intval($_GET['id']) : null;
	if (! $itemId) return false;

	$resData = array();

	// если значение не найдено, то добавляем
	if (isset($_SESSION['cart']) && array_search($itemId, $_SESSION['cart']) === false){
		$_SESSION['cart'][] = $itemId;
		$resData['cntItems'] = count($_SESSION['cart']);
		$resData['success'] = 1;
	} else {
		$resData['success'] = 0;
	}
	echo json_encode($resData);
}

/** Удаление продукта из корзины
 * @return json информация об операции (успех, кол-во элементов в корзине)
 */
function removefromcartAction(){
	$itemId = isset($_GET['id']) ? intval($_GET['id']) : null;
	if (! $itemId) exit();

	$resData = array();
	$key = array_search($itemId, $_SESSION['cart']);
	if ($key !== false){
		unset($_SESSION['cart'][$key]);
		$resData['success'] = 1;
		$resData['cntItems'] = count($_SESSION['cart']);
	} else {
		$resData['success'] = 0;
	}

	echo json_encode($resData);
}

/** 
 * Формирование страницы корзины (/cart/)
 */
function indexAction($smarty){
	$itemsIds = isset($_SESSION['cart']) ? $_SESSION['cart'] : null;

	$rsCategories = getAllMainCatsWithChildren();
	$rsProducts = isset($itemsIds) ? getProductsFromArray($itemsIds) : array();

	$smarty->assign('pageTitle', 'Корзина');
	$smarty->assign('rsCategories', $rsCategories);
	$smarty->assign('rsProducts', $rsProducts);

	loadTemplate($smarty, 'header');
	loadTemplate($smarty, 'cart');
	loadTemplate($smarty, 'footer');
}

/**
 * Формирование страницы заказа (/cart/order/)
 */
function orderAction($smarty){
	// получаем массив идентификаторов (ID) товаров из корзины
	$itemsIds = getArr($_SESSION, 'cart');

	// если корзина пуста, то редиректим на главную корзины
	if(! $itemsIds){
		redirect('/cart/');
		return;
	}

	// получаем из массива $_POST количество покупаемых товаров
	$itemsCnt = array();
	foreach($itemsIds as $item){
		// формируем ключ для массива POST
		$postVar = 'itemCnt_' . $item;
		// создаем элемент массива количества покупаемого товара
		// ключ массива - ID товара, значение - количество товара
		// $itemsCnt[1] = 3; товар с ID == 1 покупают 3 штуки
		$itemsCnt[$item] = getArr($_POST, $postVar);
	}

	// получаем список продуктов по массиву корзины
	$rsProducts = getProductsFromArray($itemsIds);

	/** добавляем каждому продукту дополнительное поле
	 * "realPrice = количество продуктов помноженое на цену продукта"
	 * "cnt" = количество покупаемого товара
	 *
	 * &$item - для того чтобы при изменении переменной $item менялся и элемент массива $rsProducts
	 */
	$i = 0;
	foreach($rsProducts as &$item){
		$item['cnt'] = getArr($itemsCnt, $item['id']);
		if($item['cnt']){
			$item['realPrice'] = $item['cnt'] * $item['price'];
		} else {
			// если вдруг получилось так, что товар в корзине есть, а количество == нулю, то удаляем этот товар
			unset($rsProducts[$i]);
		}
		$i++;
	}

	// если после удаления товара в корзине не осталось, прерываем функцию
	if(! $rsProducts){
		echo 'Корзина пуста';
		return;
	}

	// полученный массив покупаемы товаров помещаем в сессионную переменную
	$_SESSION['saleCart'] = $rsProducts;

	$rsCategories = getAllMainCatsWithChildren();

	// hideLoginBox переменная - флаг, для того, чтобы спрятать блоки логина и регистрации в боковой панели
	if(! isset($_SESSION['user'])){
		$smarty->assign('hideLoginBox', 1);
	}

	$smarty->assign('pageTitle', 'Заказ');
	$smarty->assign('rsCategories', $rsCategories);
	$smarty->assign('rsProducts', $rsProducts);

	loadTemplate($smarty, 'header');
	loadTemplate($smarty, 'order');
	loadTemplate($smarty, 'footer');
}