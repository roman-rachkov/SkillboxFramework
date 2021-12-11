<main class="shop-page">
    <header class="intro">
        <div class="intro__wrapper">
            <h1 class=" intro__title">COATS</h1>
            <p class="intro__info">Collection 2018</p>
        </div>
    </header>
    <section class="shop container">
        <section class="shop__filter filter">
            <form>
                <div class="filter__wrapper categories">
                    <b class="filter__title ">Категории</b>
                    <? printMenu($categories, 'categories', 'filter__list', SORT_ASC); ?>
                </div>
                <div class="filter__wrapper price-range">
                    <b class="filter__title">Фильтры</b>
                    <div class="filter__range range">
                        <span class="range__info">Цена</span>
                        <div class="range__line" aria-label="Range Line"></div>
                        <div class="range__res">
                            <span class="range__res-item min-price" data-min="<?= isset($_GET['minPrice']) ? $_GET['minPrice'] : 350?>"><?= isset($_GET['minPrice']) ? $_GET['minPrice'] : 350?> руб.</span>
                            <span class="range__res-item max-price" data-max="<?= isset($_GET['maxPrice']) ? $_GET['maxPrice'] : 32000?>"><?= isset($_GET['maxPrice']) ? $_GET['maxPrice'] : 32000?> руб.</span>
                        </div>
                    </div>
                </div>

                <fieldset class="custom-form__group">
                    <input type="checkbox" name="new" id="new"
                           class="custom-form__checkbox" <?= (isset($get['new']) && $get['new']) ? 'checked' : '' ?> >
                    <label for="new" class="custom-form__checkbox-label custom-form__info" style="display: block;">Новинка</label>
                    <input type="checkbox" name="sale" id="sale"
                           class="custom-form__checkbox" <?= (isset($get['sale']) && $get['sale']) ? 'checked' : '' ?>>
                    <label for="sale" class="custom-form__checkbox-label custom-form__info" style="display: block;">Распродажа</label>
                </fieldset>
                <button class="button" type="submit" style="width: 100%">Применить</button>
            </form>
        </section>

        <div class="shop__wrapper">
            <section class="shop__sorting">
                <div class="shop__sorting-item custom-form__select-wrapper">
                    <select class="custom-form__select" name="order">
                        <option hidden="" value="price">Сортировка</option>
                        <option value="price" <?= isset($_GET['order']) && $_GET['order'] == 'price' ? 'selected' : '' ?>>По цене</option>
                        <option value="title" <?= isset($_GET['order']) && $_GET['order'] == 'title' ? 'selected' : '' ?>>По названию</option>
                    </select>
                </div>
                <div class="shop__sorting-item custom-form__select-wrapper">
                    <select class="custom-form__select" name="sort">
                        <option hidden="" value="asc">Порядок</option>
                        <option value="asc" <?= isset($_GET['sort']) && $_GET['sort'] == 'asc' ? 'selected' : '' ?>>По возрастанию</option>
                        <option value="desc" <?= isset($_GET['sort']) && $_GET['sort'] == 'desc' ? 'selected' : '' ?>>По убыванию</option>
                    </select>
                </div>
                <p class="shop__sorting-res">Найдено <?= declOfNum(count($goods), ["модель", "модели", "моделей"]); ?></p>
            </section>
            <section class="shop__list">
                <?php if (isset($goods)):
                    foreach ($goods as $good): ?>
                        <article class="shop__item product" data-id="<?= $good['id'] ?>" tabindex="0">
                            <div class="product__image">
                                <img src="/upload/goods/<?= $good['photo'] ?>" alt="product-name">
                            </div>
                            <p class="product__name"><?= $good['title'] ?></p>
                            <span class="product__price"><?= number_format($good['price'], 2, '.', ' ') ?></span>
                        </article>
                    <? endforeach;
                endif; ?>
            </section>

            <? printPagination($get['p'], $maxPage); ?>

        </div>
    </section>
    <section class="shop-page__order" hidden="">
        <div class="shop-page__wrapper">
            <h2 class="h h--1">Оформление заказа</h2>
            <form action="/order" method="post" class="custom-form js-order">
                <input type="hidden" name="id">
                <fieldset class="custom-form__group">
                    <legend class="custom-form__title">Укажите свои личные данные</legend>
                    <p class="custom-form__info">
                        <span class="req">*</span> поля обязательные для заполнения
                    </p>
                    <div class="custom-form__column">
                        <label class="custom-form__input-wrapper" for="surname">
                            <input id="surname" class="custom-form__input" type="text" name="surname" required="">
                            <p class="custom-form__input-label">Фамилия <span class="req">*</span></p>
                        </label>
                        <label class="custom-form__input-wrapper" for="name">
                            <input id="name" class="custom-form__input" type="text" name="name" required="">
                            <p class="custom-form__input-label">Имя <span class="req">*</span></p>
                        </label>
                        <label class="custom-form__input-wrapper" for="thirdName">
                            <input id="thirdName" class="custom-form__input" type="text" name="thirdName">
                            <p class="custom-form__input-label">Отчество</p>
                        </label>
                        <label class="custom-form__input-wrapper" for="phone">
                            <input id="phone" class="custom-form__input" type="tel" name="phone" required="">
                            <p class="custom-form__input-label">Телефон <span class="req">*</span></p>
                        </label>
                        <label class="custom-form__input-wrapper" for="email">
                            <input id="email" class="custom-form__input" type="email" name="email" required="">
                            <p class="custom-form__input-label">Почта <span class="req">*</span></p>
                        </label>
                    </div>
                </fieldset>
                <fieldset class="custom-form__group js-radio">
                    <legend class="custom-form__title custom-form__title--radio">Способ доставки</legend>
                    <input id="dev-no" class="custom-form__radio" type="radio" name="delivery" value="dev-no"
                           checked="">
                    <label for="dev-no" class="custom-form__radio-label">Самовывоз</label>
                    <input id="dev-yes" class="custom-form__radio" type="radio" name="delivery" value="dev-yes">
                    <label for="dev-yes" class="custom-form__radio-label">Курьерная доставка</label>
                    <div class="delivery-cost" data-minPrice="<?=getSetting('minimal_price_free_delivery')?>" data-deliveryCost="<?=getSetting('delivery_price')?>">

                    </div>
                </fieldset>
                <div class="shop-page__delivery shop-page__delivery--no">
                    <table class="custom-table">
                        <caption class="custom-table__title">Пункт самовывоза</caption>
                        <tr>
                            <td class="custom-table__head">Адрес:</td>
                            <td>Москва г, Тверская ул,<br> 4 Метро «Охотный ряд»</td>
                        </tr>
                        <tr>
                            <td class="custom-table__head">Время работы:</td>
                            <td>пн-вс 09:00-22:00</td>
                        </tr>
                        <tr>
                            <td class="custom-table__head">Оплата:</td>
                            <td>Наличными или банковской картой</td>
                        </tr>
                        <tr>
                            <td class="custom-table__head">Срок доставки:</td>
                            <td class="date">13 декабря—15 декабря</td>
                        </tr>
                    </table>
                </div>
                <div class="shop-page__delivery shop-page__delivery--yes" hidden="">
                    <fieldset class="custom-form__group">
                        <legend class="custom-form__title">Адрес</legend>
                        <p class="custom-form__info">
                            <span class="req">*</span> поля обязательные для заполнения
                        </p>
                        <div class="custom-form__row">
                            <label class="custom-form__input-wrapper" for="city">
                                <input id="city" class="custom-form__input" type="text" name="city">
                                <p class="custom-form__input-label">Город <span class="req">*</span></p>
                            </label>
                            <label class="custom-form__input-wrapper" for="street">
                                <input id="street" class="custom-form__input" type="text" name="street">
                                <p class="custom-form__input-label">Улица <span class="req">*</span></p>
                            </label>
                            <label class="custom-form__input-wrapper" for="home">
                                <input id="home" class="custom-form__input custom-form__input--small" type="text"
                                       name="home">
                                <p class="custom-form__input-label">Дом <span class="req">*</span></p>
                            </label>
                            <label class="custom-form__input-wrapper" for="aprt">
                                <input id="aprt" class="custom-form__input custom-form__input--small" type="text"
                                       name="aprt">
                                <p class="custom-form__input-label">Квартира <span class="req">*</span></p>
                            </label>
                        </div>
                    </fieldset>
                </div>
                <fieldset class="custom-form__group shop-page__pay">
                    <legend class="custom-form__title custom-form__title--radio">Способ оплаты</legend>
                    <input id="cash" class="custom-form__radio" type="radio" name="pay" value="cash">
                    <label for="cash" class="custom-form__radio-label">Наличные</label>
                    <input id="card" class="custom-form__radio" type="radio" name="pay" value="card" checked="">
                    <label for="card" class="custom-form__radio-label">Банковской картой</label>
                </fieldset>
                <fieldset class="custom-form__group shop-page__comment">
                    <legend class="custom-form__title custom-form__title--comment">Комментарии к заказу</legend>
                    <textarea class="custom-form__textarea" name="comment"></textarea>
                </fieldset>
                <button class="button" type="submit">Отправить заказ</button>
            </form>
        </div>
    </section>
    <section class="shop-page__popup-end" hidden="">
        <div class="shop-page__wrapper shop-page__wrapper--popup-end">
            <h2 class="h h--1 h--icon shop-page__end-title">Спасибо за заказ!</h2>
            <p class="shop-page__end-message">Ваш заказ успешно оформлен, с вами свяжутся в ближайшее время</p>
            <button class="button">Продолжить покупки</button>
        </div>
    </section>
</main>