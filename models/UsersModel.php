<?php
/**
 * Модель для таблицы пользователей (users)
 */

/** Регистрация нового пользователя
 * @return array массив даных нового пользователя
 */
function registerNewUser($email, $pwdMD5, $name, $phone, $adress){
	$email = getVar($email);
	$name = getVar($name);
	$phone = getVar($phone);
	$adress = getVar($adress);

	$sql = "INSERT INTO `users` (`email`, `pwd`, `name`, `phone`, `adress`) VALUES ('{$email}', '{$pwdMD5}', '{$name}', '{$phone}', '{$adress}')";
	$rs = mysql_query($sql);


	if ($rs){
		$sql = "SELECT * FROM `users` WHERE (`email` = '{$email}' and `pwd` = '{$pwdMD5}') LIMIT 1";
		$rs = mysql_query($sql);
		$rs = createSmartyRsArray($rs);

		if (isset($rs[0])){
			$rs['success'] = 1;
		} else {
			$rs['success'] = 0;
		}
	} else {
		$rs['success'] = 0;
	}

	return $rs;
}

/** Проверка параметров для регистрации пользователя
 * @return array результат
 */
function checkRegisterParams($email, $pwd1, $pwd2){
	$res = null;

	if (! $email){
		$res['success'] = false;
		$res['message'] = 'Введите email';
	} elseif (! $pwd1){
		$res['success'] = false;
		$res['message'] = 'Введите пароль';
	} elseif (! $pwd2){
		$res['success'] = false;
		$res['message'] = 'Введите повтор пароля';
	} elseif ($pwd1 != $pwd2){
		$res['success'] = false;
		$res['message'] = 'Пароли не совпадают';
	}

	/*if (! $email){
		$res['success'] = false;
		$res['message'] = 'Введите email';
	}

	if (! $pwd1){
		$res['success'] = false;
		$res['message'] = 'Введите пароль';
	}

	if (! $pwd2){
		$res['success'] = false;
		$res['message'] = 'Введите повтор пароля';
	}

	if ($pwd1 != $pwd2){
		$res['success'] = false;
		$res['message'] = 'Пароли не совпадают';
	}*/
	
	return $res;
}

/** Проверка почты (есть ли email адрес в БД)
 * @return array массив - строка из таблицы users, либо пустой массив
 */
function checkUserEmail($email){
	$email = mysql_real_escape_string($email);

	$sql = "SELECT `id` FROM `users` WHERE `email` = '{$email}'";
	$rs = mysql_query($sql);
	$rs = createSmartyRsArray($rs);

	return $rs;
}

/** Проверка параметров для авторизации пользователя
* return array результат
*/
function checkLoginParams($loginEmail, $loginPwd){
	$res = null;

	if (! $loginEmail or ! $loginPwd){
		$res['success'] = null;
		$res['message'] = 'Неправильная почта или пароль';
	}

	return $res;
}

/** Авторизация пользователя
 * return array массив данных пользователя и результат
 */
function loginUser($loginEmail, $loginPwdMD5){
	$loginEmail = getVar($loginEmail);

	if (isset($loginEmail) && $loginEmail !== ''){
		$sql = "SELECT * FROM `users` WHERE (`email` = '{$loginEmail}' and `pwd` = '{$loginPwdMD5}') LIMIT 1";
		$rs = mysql_query($sql);
		$rs = createSmartyRsArray($rs);

		if (isset($rs[0])){
			$rs['success'] = 1;
		} else {
			$rs['success'] = 0;
		}
	} else {
		$rs['success'] = 0;
	}

	return $rs;
}

/** Изменение данных пользователя
 * return boolean TRUE в случае успеха
 */
function updateUserData($name, $phone, $adress, $pwd1, $pwd2, $curPwd){
	$email = getVar($_SESSION['user']['email']);
	$name = getVar($name);
	$adress = getVar($adress);
	$phone = getVar($phone);
	$pwd1 = trim($pwd1);
	$pwd2 = trim($pwd2);
	$curPwd = getVar($curPwd);

	$newPwd = null;
	if ($pwd1 && ($pwd1 == $pwd2)){
		$newPwd = md5($pwd1);
	}

	$sql = "UPDATE `users` SET ";

	if($newPwd){
		$sql .= "`pwd` = '{$newPwd}',";
	}

	$sql .= " `name` = '{$name}', 
	`phone` = '{$phone}', 
	`adress` = '{$adress}' 
	WHERE (`email` = '{$email}' 
	AND `pwd` = '{$curPwd}') 
	LIMIT 1";

	$rs = mysql_query($sql);
	if ($newPwd){
		$res['newPwd'] = $newPwd;
	}

	$res['rs'] = $rs;
	return $res;
}