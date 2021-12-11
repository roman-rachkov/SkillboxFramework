<main class="page-add">
    <h1 class="h h--1" <?= !empty($good) ? 'style="font-size:60px"' : '' ?>><?= empty($good) ? 'Добавление товара' : 'Редактирование товара' ?></h1>
    <form class="custom-form" action="/admin/goods/add" method="post">
        <?if(!empty($good)):?>
        <input type="hidden" value="<?=$good['id']?>" name="id">
        <?endif;?>
        <fieldset class="page-add__group custom-form__group">
            <legend class="page-add__small-title custom-form__title">Данные о товаре</legend>
            <label for="product-name" class="custom-form__input-wrapper page-add__first-wrapper">
                <input type="text" class="custom-form__input" name="product-name" id="product-name" value="<?= !empty($good) ? $good['title'] : '' ?>">
                <p class="custom-form__input-label">
                    <?= !empty($good) ? $good['title'] : 'Название товара' ?>
                </p>
            </label>
            <label for="product-price" class="custom-form__input-wrapper">
                <input type="text" class="custom-form__input" name="product-price" id="product-price" value="<?= !empty($good) ? $good['price'] : '' ?>">
                <p class="custom-form__input-label">
                    <?= !empty($good) ? $good['price'] : 'Цена товара' ?>
                </p>
            </label>
        </fieldset>
        <fieldset class="page-add__group custom-form__group">
            <legend class="page-add__small-title custom-form__title">Фотография товара</legend>
            <ul class="add-list">
                <li class="add-list__item add-list__item--add">
                    <input type="file" name="product-photo" id="product-photo" hidden="">
                    <label for="product-photo">Добавить фотографию</label>
                </li>
                <?if(!empty($good)):?>
                    <li class="add-list__item add-list__item--active"><img src="/upload/goods/<?=$good['photo'];?>"></li>
                <?endif;?>
            </ul>
        </fieldset>
        <fieldset class="page-add__group custom-form__group">
            <legend class="page-add__small-title custom-form__title">Раздел</legend>
            <div class="page-add__select">
                <select name="category" class="custom-form__select" multiple="multiple">
                    <option hidden="">Название раздела</option>

                    <? foreach ($categories as $category): ?>
                        <option value="<?= $category['id'] ?>" <?=(!empty($good) && in_array($category['id'], array_column($good['categories'], 'id'))) ? 'selected' : ''?>><?= $category['title'] ?></option>
                    <? endforeach; ?>
                </select>
            </div>
            <input type="checkbox" name="new" id="new" class="custom-form__checkbox" <?=(!empty($good) && $good['new']) ? 'checked' : ''?>>
            <label for="new" class="custom-form__checkbox-label" >Новинка</label>
            <input type="checkbox" name="sale" id="sale" class="custom-form__checkbox" <?=(!empty($good) && $good['sale']) ? 'checked' : ''?>>
            <label for="sale" class="custom-form__checkbox-label">Распродажа</label>
        </fieldset>
        <button class="button" type="submit">Добавить товар</button>
    </form>
    <section class="shop-page__popup-end page-add__popup-end" hidden="">
        <div class="shop-page__wrapper shop-page__wrapper--popup-end">
            <h2 class="h h--1 h--icon shop-page__end-title">Товар успешно добавлен</h2>
        </div>
    </section>
</main>
