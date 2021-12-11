<main class="page-order">
    <h1 class="h h--1">Список заказов</h1>
    <ul class="page-order__list">
        <? foreach ($orders as $order): ?>
            <li class="order-item page-order__item">
                <div class="order-item__wrapper">
                    <div class="order-item__group order-item__group--id">
                        <span class="order-item__title">Номер заказа</span>
                        <span class="order-item__info order-item__info--id"><?= $order['id'] ?></span>
                    </div>
                    <div class="order-item__group">
                        <span class="order-item__title">Сумма заказа</span>
                        <?= number_format($order['amount'], 2, '.', ' ') ?> руб.
                    </div>
                    <button class="order-item__toggle"></button>
                </div>
                <div class="order-item__wrapper">
                    <div class="order-item__group order-item__group--margin">
                        <span class="order-item__title">Заказчик</span>
                        <span class="order-item__info"><?= trim($order['client']['surname'] . ' ' . $order['client']['name'] . ' ' . $order['client']['thirdname']); ?></span>
                    </div>
                    <div class="order-item__group">
                        <span class="order-item__title">Номер телефона</span>
                        <span class="order-item__info"><?= $order['client']['phone'] ?></span>
                    </div>
                    <div class="order-item__group">
                        <span class="order-item__title">Способ доставки</span>
                        <span class="order-item__info"><?= $order['delivery'] == 'delivery' ? 'Доставка' : 'Самовывоз'; ?></span>
                    </div>
                    <div class="order-item__group">
                        <span class="order-item__title">Способ оплаты</span>
                        <span class="order-item__info"><?= $order['payment'] == 'card' ? 'Картой' : 'Наличными'; ?></span>
                    </div>
                    <div class="order-item__group order-item__group--status">
                        <span class="order-item__title">Статус заказа</span>
                        <span class="order-item__info order-item__info--<?= $order['status'] ? 'yes' : 'no' ?>"><?= $order['status'] ? 'Выполнено' : 'Не выполнено' ?></span>
                        <button class="order-item__btn" data-id="<?= $order['id']; ?>">Изменить</button>
                    </div>
                </div>
                <div class="order-item__wrapper">
                    <div class="order-item__group">
                        <span class="order-item__title">Адрес доставки</span>
                        <span class="order-item__info"><?= $order['client']['adress']; ?></span>
                    </div>
                </div>
                <div class="order-item__wrapper">
                    <div class="order-item__group">
                        <span class="order-item__title">Комментарий к заказу</span>
                        <span class="order-item__info"><?= $order['comment'] ?></span>
                    </div>
                </div>
            </li>
        <? endforeach; ?>
    </ul>
    <br><br><br>
    <? printPagination($p, $maxPage); ?>
</main>