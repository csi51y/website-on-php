<?php

/**
 *  Модель для таблиц продукции (products)
 */

/** Получаем последние добавленные товары
 * integer $limit Лимит товаров
 * @return array Массив товаров
 */
function getLastProducts($limit = null){
	$sql = "SELECT * FROM `products` ORDER BY `id` DESC";
	if($limit){
		$sql .= " LIMIT {$limit}";
	}
	$rs = mysql_query($sql);
	return createSmartyRsArray($rs);
}

/** Получить продукты для категории $itemId
 * $itemId ID категории
 */
function getProductsByCat($itemId){
	$itemId = intval($itemId);
	$sql = "SELECT * FROM `products` WHERE `category_id` = '{$itemId}'";
	$rs = mysql_query($sql);

	return createSmartyRsArray($rs);
}

/** Получить данные товара по ID
 * @return array массив данных товара
 */
function getProductById($itemId){
	$itemId = intval($itemId);
	$sql = "SELECT * FROM `products` WHERE `id` = '{$itemId}'";
	$rs = mysql_query($sql);

	return mysql_fetch_assoc($rs);
}

/** Получить список продуктов из массива идентивикаторов (ID's)
 * @return array массив данных продуктов
 */
function getProductsFromArray($itemsIds){
	$strIds = implode($itemsIds, ', ');
	$sql = "SELECT * FROM `products` WHERE `id` in ({$strIds})";
	$rs = mysql_query($sql);

	return createSmartyRsArray($rs);
}