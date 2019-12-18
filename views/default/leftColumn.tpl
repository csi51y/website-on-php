

		<div id="leftColumn">
			<div id="leftMenu">
				<div class="menuCaption">Меню:</div>
				{foreach $rsCategories as $item}
					{* <a href="?controller=category&id={$item['id']}">{$item['name']}</a><br /> *}
					<a href="/category/{$item['id']}/">{$item['name']}</a><br />

					{if isset($item['children'])}
					{foreach $item['children'] as $itemChild}
						--<a href="/category/{$itemChild['id']}/">{$itemChild['name']}</a><br />
					{/foreach}
					{/if}

				{/foreach}
			</div>

			{if isset($arUser)}
				<div id="userBox">
					<a href="/user/" id="userlink">{$arUser['displayName']}</a><br />
					<a href="/user/logout/" id="onclickLogout">Выход</a>
				</div>
			{else}

			<div id="userBox" class="hideme">
				<a href="#" id="userLink"></a><br />
				<a href="/user/logout/" id="onclickLogout">Выход</a>
			</div>

			<div id="loginBox">
				<div class="menuCaption">Авторизация</div>
				<input type="text" id="loginEmail" name="loginEmail" placeholder="Почта" value=""><br />
				<input type="password" name="loginPwd" id="loginPwd" placeholder="Пароль" value=""><br />
				<input type="button" id="onclickLogin" value="Войти">
			</div>

			<div id="registerBox">
				<div class="menuCaption showHidden" id="onclickShowRegisterBox">Регистрация</div>
				<div id="registerBoxHidden" class="hideme">
					email:<br />
					<input type="text" id="email" name="email" value=""><br />
					пароль: <br />
					<input type="password" id="pwd1" name="pwd1" value=""><br />
					повторите пароль:<br />
					<input type="password" id="pwd2" name="pwd2" value=""><br />
					<input type="button" id="onclickRegisterNewUser" value="Зарегистрироваться">
				</div>
			</div>
			{/if}

			<div class="menuCaption">Корзина</div>
			<a href="/cart/" title="Перейти в корзину">В корзине</a>
			<span id="cartCntItems">
				{if $cartCntItems > 0}{$cartCntItems}{else}пусто{/if}
			</span>
		</div>