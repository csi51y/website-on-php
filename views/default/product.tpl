{* страница продукта *}
<h3>{$rsProduct['name']}</h3>

<img src="/images/products/{$rsProduct['image']}" height="350px"><br />
<br />Стоимость: {$rsProduct['price']}

<a id="removeCart_{$rsProduct['id']}" {if ! $itemInCart}class="hideme"{/if} alt="Удалить из корзины" onClick="removeFromCart({$rsProduct['id']}); return false;" href="#">Удалить из корзины</a>
<a id="addCart_{$rsProduct['id']}" {if $itemInCart}class="hideme"{/if} alt="Добавить в корзину" onClick="addToCart({$rsProduct['id']}); return false;" href="#">Добавить в корзину</a>
<p> Описание <br />{$rsProduct['description']}</p>