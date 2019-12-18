<?php
/**
* Основные функции
*/

/** Формирование запрашиваемой страницы
* string $controllerName название контроллера
* string $actionName название функции обработки страницы
*/
function loadPage($smarty, $controllerName, $actionName = 'index'){
	include_once PathPrefix . $controllerName . PathPostfix;
	$function = $actionName . 'Action';
	$function($smarty);
}

// Загрузка шаблона
// object $smarty объект шаблонизации
// string $templateName название файла шаблона
function loadTemplate($smarty, $templateName){
	TemplatePrefix . $templateName .= TemplatePostfix;
	$smarty->display($templateName);
}

/** Преобразование результата работы функции выборки в ассоциативный массив
 * recordset $rs набор строк - результат работы SELECT
 * @return array
 */
function createSmartyRsArray($rs){
	if(! $rs) return false;
	$smartyRs = array();
	while($row = mysql_fetch_assoc($rs)){
		$smartyRs[] = $row;
	}
	return $smartyRs;
}

/** Редирект
 * @param string #url адрес для перенаправления
 */
function redirect($url = '/'){
	header("Location: {$url}");
	exit;
}

// Функция отладки. Останавливает работу программы выводя значение переменной $value
// $value переменная для вывода ее на страницу
function d($value = null, $die = 1){
	echo 'Debug: <br /><pre>';
	print_r($value);
	echo '</pre>';

	if($die) die;
}

// Функиця проверки наличия значения у переменной, отличного от NULL
function getArr($array, $index, $default = null){
	return isset($array[$index]) ? $array[$index] : $default;
}

// Функция экранирует спец. символы и приобазовывает их в HTML-сущности
function getVar($var){
	return htmlspecialchars(mysql_real_escape_string($var));
}