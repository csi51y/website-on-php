{* Страница заказа *}

<h2>Данные заказа</h2>
<form action="/cart/saveorder/" id="frmOrder" method="POST">
	<table>
		<tr>
			<td>№</td>
			<td>Наименование</td>
			<td>Количество</td>
			<td>Цена за единицу</td>
			<td>Стоимость</td>
		</tr>

		{foreach $rsProducts as $item name=products}
			<tr>
				<td>{$smarty.foreach.products.iteration}</td>
				<td><a href="/product/{$item['id']}/">{$item['name']}</a></td>
				<td>
					<span id="itemCnt_{$item['id']}">
						<input type="hidden" name="itemCnt_{$item['id']}" value="{$item['cnt']}">
						{$item['cnt']}
					</span>
				</td>
			</tr>
		{/foreach}

	</table>
</form>