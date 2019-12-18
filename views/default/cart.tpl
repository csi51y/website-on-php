{* шаблон корзины *}

<h1>Корзина</h1>

{if ! $rsProducts}
В корзине пусто.

{else}
<form action="/cart/order/" method="POST">
	<h2>Данные заказа</h2>
	<table>
		<tr>
			<td>№</td>
			<td>Наименование</td>
			<td>Количество</td>
			<td>Цена за единицу</td>
			<td>Итого</td>
			<td>Действие</td>
		</tr>
		{foreach $rsProducts as $item name=products}
			<tr>
				<td>{$smarty.foreach.products.iteration}</td>
				<td><a href="/product/{$item['id']}/">{$item['name']}</a><br /></td>
				<td id="onchangeConversionPrice">
					<input type="text" name="itemCnt_{$item['id']}" id="itemCnt_{$item['id']}" value="1" data-itemid="{$item['id']}">
				</td>
				<td>
					<span id="itemPrice_{$item['id']}" price="{$item['price']}">{$item['price']}</span>
				</td>
				<td>
					<span id="itemRealPrice_{$item['id']}">{$item['price']}</span>
				</td>
				<td id="onclickRemOrAddCart" data-itemid="{$item['id']}">
					<a id="removeCart_{$item['id']}" data-type="remove" title="Удалить из корзины" href="#">Удалить</a>
					<a id="addCart_{$item['id']}" data-type="add" class="hideme" title="Восстановить товар" href="#">Восстановить</a>
				</td>
			</tr>
		{/foreach}
	</table>
<input type="submit" value="Оформить заказ">
</form>
{/if}