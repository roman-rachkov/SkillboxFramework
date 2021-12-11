<main class="page-settings">
    <h1 class="h h--1">Настройки</h1>
    <form class="custom-form" action="/admin/settings" method="post">
        <label for="minimal-price">Минимальная сумма для бесплатной доставки</label>
        <input type="number" class="custom-form__input" id="minimal-price" name="minimal_price_free_delivery" value="<?=getSetting('minimal_price_free_delivery')?>">
        <label for="delivery-price">Стоимость доставки</label>
        <input type="number" class="custom-form__input" id="delivery-price" name="delivery_price" value="<?=getSetting('delivery_price')?>">
        <input class="button" type="submit" name="login" value="Сохранить">
    </form>
</main>
