<?php
/**
 * Контоллер функций пользователя
 */

// подключаем модели
include_once '../models/CategoriesModel.php';
## include_once '../models/OrdersModel,php';
include_once '../models/UsersModel.php';

/** AJAX регистрация пользователя.
 * Инициализация сессионой переменной ($_SESSION['user'])
 * @return json массив данных нового пользователя
 */
function registerAction(){
	$email = getArr($_REQUEST, 'email');
	$email = trim($email);
	$pwd1 = getArr($_REQUEST, 'pwd1');
	$pwd2 = getArr($_REQUEST, 'pwd2');

	$phone = getArr($_REQUEST, 'phone');
	$adress = getArr($_REQUEST, 'adress');
	$name = getArr($_REQUEST, 'name');
	$name = trim($name);

	$resData = null;
	$resData = checkRegisterParams($email, $pwd1, $pwd2);

	if (! $resData && checkUserEmail($email)){
		$resData['success'] = false;
		$resData['message'] = "Пользователь с таким email('{$email}') уже существует";
	}

	if (! $resData){
		$pwdMD5 = md5($pwd1);

		$userData = registerNewUser($email, $pwdMD5, $name, $phone, $adress);
		if ($userData['success']){
			$resData['message'] = 'Пользователь успешно зарегистрирован';
			$resData['success'] = 1;

			$userData = $userData[0];
			$resData['userName'] = $userData['name'] ? $userData['name'] : $userData['email'];
			$resData['userEmail'] = $email;

			$_SESSION['user'] = $userData;
			$_SESSION['user']['displayName'] = $userData['name'] ? $userData['name'] : $userData['email'];
		} else {
			$resData['success'] = 0;
			$resData['message'] = 'Ошибка регистрации';
		}
	}

	echo json_encode($resData);
	#echo print_r($resData);
}

// Разлогинивание пользователя
function logoutAction(){
	if(isset($_SESSION['user'])){
		unset($_SESSION['user']);
		unset($_SESSION['cart']);
	}

	redirect('/');
}

/** AJAX авторизация пользователя
 * инициализация сессионной переменной ($_SESSION['user'])
 * return json массив данных пользователя
 */
function loginAction(){
	$loginEmail = getArr($_REQUEST, 'loginEmail');
	$loginEmail = trim($loginEmail);
	$loginPwd = getArr($_REQUEST, 'loginPwd');
	$loginPwd = trim($loginPwd);

	$resData = null;

	$resData = checkLoginParams($loginEmail, $loginPwd);
	if (! $resData){
		$loginPwdMD5 = md5($loginPwd);
		$userData = loginUser($loginEmail, $loginPwdMD5);

		if ($userData['success']){
			$resData['success'] = 1;

			$userData = $userData[0];
			$resData['userName'] = $userData['name'] ? $userData['name'] : $userData['email'];
			#$resData['userEmail'] = $loginEmail;

			$_SESSION['user'] = $userData;
			$_SESSION['user']['displayName'] = $resData['userName'];

			#$resData = $_SESSION['user'];
		} else {
			$resData['success'] = 0;
			$resData['message'] = 'Неправильная почта или пароль';
		}
	}

	echo json_encode($resData);
}

/** Формирование главной страницы пользователя
 * link /user/
 */
function indexAction($smarty){
	 // если пользователь не залогинен, то редирект на главную страницу
	if(! isset($_SESSION['user'])){
		redirect('/');
	}

	// получаем список категорий для меню
	$rsCategories = getAllMainCatsWithChildren();

	$smarty->assign('pageTitle', 'Страница пользователя');
	$smarty->assign('rsCategories', $rsCategories);

	loadTemplate($smarty, 'header');
	loadTemplate($smarty, 'user');
	loadTemplate($smarty, 'footer');
}

/** Обновление данных пользователя
 * return json результаты выполнения функции 
 */
function updateAction(){
	//> если пользователь не залогинен, то редирект на главную
	if(! isset($_SESSION['user'])){
		redirect('/');
	}
	//<

	//> Инициализация переменных
	$resData = array();
	$phone = getArr($_REQUEST, 'phone');
	$adress = getArr($_POST, 'adress');
	$name = getArr($_REQUEST, 'name');
	$pwd1 = getArr($_REQUEST, 'pwd1');
	$pwd2 = getArr($_REQUEST, 'pwd2');
	$curPwd = getArr($_REQUEST, 'curPwd');
	//<

	// Проверка правильности веденного пароля
	$curPwdMD5 = md5($curPwd);
	if(! $curPwd || ($_SESSION['user']['pwd'] != $curPwdMD5)){
		$resData['success'] = 0;
		$resData['message'] = 'Введен неверный пароль';

		echo json_encode($resData);
		return false;
	}

	// обновление данных пользователя
	$res = updateUserData($name, $phone, $adress, $pwd1, $pwd2, $curPwdMD5);
	if($res['rs']){
		$resData['success'] = 1;
		$resData['message'] = 'Данные сохранены';
		$resData['userName'] = $name;
		// Проверка правильности ввода нового пароля
		if (! $res['newPwd'] && ($pwd1 or $pwd2) && $pwd1 != $pwd2){
			$resData['message'] .= '. Но пароль сохранен не был т.к. введенные пароли не совпадают';
		}

		$_SESSION['user']['name'] = $name;
		$_SESSION['user']['phone'] = $phone;
		$_SESSION['user']['adress'] = $adress;
		$_SESSION['user']['pwd'] = $res['newPwd'] ? $res['newPwd'] : $curPwdMD5;
		$_SESSION['user']['displayName'] = $name ? $name : $_SESSION['user']['email'];
	} else {
		$resData['success'] = null;
		$resData['message'] = 'Ошибка сохранения данных';
	}
	
	echo json_encode($resData);
}