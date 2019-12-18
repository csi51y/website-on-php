/** 
* Функция добавления товара в корзину
* @param integer itemId ID продукта
* @return в случае успеха обновятся данные корзины на странице
*/
function addToCart(itemId){
	console.log("js - addToCart()");
	$.ajax({
		type: 'POST',
		async: true,
		url: "/cart/addtocart/" + itemId + '/',
		dataType: 'json',
		success: function(data){
			if(data['success']){
				$('#cartCntItems').html(data['cntItems']);

				$('#addCart_'+ itemId).hide();
				$('#removeCart_'+ itemId).show();
			}
		}
	});
}

/** 
* Функция удаления товара из корзины
* @param itemId ID товара
* @return в случае успеха обновятся данные корзины на странице
*/
function removeFromCart(itemId){
	console.log("js - removeFromCart("+itemId+")");
	$.ajax({
		type: 'POST',
		async: true,
		url: "/cart/removefromcart/" + itemId + '/',
		dataType: 'json',
		success: function(data){
			if(data['success']){
				$('#cartCntItems').html(data['cntItems']);
				$('#addCart_'+ itemId).show();
				$('#removeCart_'+ itemId).hide();
			}
		}
	});
}

/** Подсчет стоимости купленного товара
* @param integer itemId ID продукта
*/
function conversionPrice(itemId){
	var newCnt = $('#itemCnt_'+ itemId).val();
	var itemPrice = $('#itemPrice_'+ itemId).attr('price');
	var itemRealPrice = newCnt * itemPrice;

	$('#itemRealPrice_' + itemId).html(itemRealPrice);
}

// Получение данных с формы
function getData(obj_form){
	var hData = {};
	$('input, textarea, select', obj_form).each(function(){
		if(this.name && this.name !=''){
			hData[this.name] = this.value;
			console.log('hData[' + this.name + '] = ' + hData[this.name]);
		}
	});
	return hData;
}

// Регистрация нового пользователя
function registerNewUser(){
	var postData = getData('#registerBox');

	$.ajax({
		type: 'POST',
		async: true,
		url: "/user/register/",
		data: postData,
		dataType: 'json',
		success: function(data){
			if(data['success']){
				alert('Регистрация прошла успешно!');

				//> блок в левом столбце
				$('#registerBox').hide();

				$('#userLink').attr('href', '/user/');
				$('#userLink').html(data['userName']);
				$('#userBox').show();
				//<

				//> страница заказа
				$('#loginBox').hide();
				//$('#btnSaveOrder').show();
				//<
			} else {
				alert(data['message']);
			}
		}
	});
}
/*function registerNewUser(){
	$.ajax({
		url: "/user/register/",
		success: function(data){
			console.log("Прибыли данные: " + data);
			alert("Прибыли данные: " + data);
		}
	});
}*/

// Разлогинивание пользователя
/*function logout(){
	$.ajax({
		url: "/user/logout/",
		success: function(){
			document.location.href = 'http://webserver:8282/';
		}
	});
}*/

// Авторизация
function login(){
	var email = $('#loginEmail').val();
	var pwd = $('#loginPwd').val();
	var postData = {'loginEmail': email, 'loginPwd': pwd};

	$.ajax({
		type: 'POST',
		async: true,
		url: "/user/login/",
		data: postData,
		dataType: 'json',
		success: function(data){
			if(data['success']){
				$('#userLink').attr('href', '/user/');
				$('#userLink').html(data['userName']);

				$('#userBox').show();
				$('#loginBox').hide();
				$('#registerBox').hide();
			} else {
				alert(data['message']);
			}
		}
	});
}

// Делегирование событий файла leftColumn.tpl
$(function(){
	$('#leftColumn').on('click', 'input, div', function(){
		
		if(($(this).attr('id')) == 'onclickLogin'){
			login();
		} else if(($(this).attr('id')) == 'onclickRegisterNewUser'){
			registerNewUser();
		} else if($(this).attr('id') == 'onclickShowRegisterBox'){
			showRegisterBox();
		}
	});
});

// Делегирование событий user.tpl
$(function(){
	$('#onclickUpdateUserData').on('click', function(){
		updateUserData();
	});
});

// Делегирование событий cart.tpl
$(function(){
	$('#onchangeConversionPrice').on('change', 'input', function(){
		var itemId = $(this).data('itemid');
		conversionPrice(itemId);
	});
});

$(function(){
	$('#onclickCheckout').on('click', funtion(){
		checkout();
	});
});

$(function(){
	$('#onclickRemOrAddCart').on('click', 'a', function(event){
		var itemId = $(event.delegateTarget).data('itemid');

		if($(this).data('type') == 'remove'){
			removeFromCart(itemId);
			event.stopImmediatePropagation();
		} else if($(this).data('type') == 'add'){
			addToCart(itemId);
			event.stopImmediatePropagation();
		}
	});
});

// Показывает и скрывает окно регистрации
function showRegisterBox(){
	$('#registerBoxHidden').slideToggle().delay(180);
}

// Обновление данных пользователя
function updateUserData(){
	console.log("js - updateUserData()");
	var phone = $('#newPhone').val();
	var adress = $('#newAdress').val();
	var pwd1 = $('#newPwd1').val();
	var pwd2 = $('#newPwd2').val();
	var curPwd = $('#curPwd').val();
	var name = $('#newName').val();

	var postData = {
		phone: phone,
		adress: adress,
		pwd1: pwd1,
		pwd2: pwd2,
		curPwd: curPwd,
		name: name};

	$.ajax({
		type: 'POST',
		async: true,
		url: "/user/update/",
		data: postData,
		dataType: 'json',
		success: function(data){
			if(data['success']){;
				$('#userLink').html(data['userName']);
				alert(data['message']);
				$(location).attr('href', 'http://webserver:8282/user/');
			} else {
				alert(data['message']);
			}
		}
	});
}

//
function checkout(){
	var postData = {};

	$.ajax({
		type: 'POST',
		async: true,
		url: "/order/",
		data: postData,
		dataType: 'json',
		success: function(data){
			if(data['success']){
				s
			} else {
				s
			}
		}
	});
}