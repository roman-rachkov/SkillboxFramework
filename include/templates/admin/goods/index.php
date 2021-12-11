<main class="page-products">
    <h1 class="h h--1">Товары</h1>
    <a class="page-products__button button" href="/admin/goods/add">Добавить товар</a>
    <?php if (!empty($goods)): ?>
        <div class="page-products__header">
            <span class="page-products__header-field">Название товара</span>
            <span class="page-products__header-field">ID</span>
            <span class="page-products__header-field">Цена</span>
            <span class="page-products__header-field">Категория</span>
            <span class="page-products__header-field">Новинка</span>
        </div>
        <ul class="page-products__list">
            <?php foreach ($goods as $good): ?>
                <li class="product-item page-products__item">
                    <b class="product-item__name"><?= $good['title'] ?></b>
                    <span class="product-item__field"><?= $good['id'] ?></span>
                    <span class="product-item__field"><?= number_format($good['price'], 2, '.', ' ') ?> руб.</span>
                    <span class="product-item__field"><?= $good['category'] ?></span>
                    <span class="product-item__field"><?= (bool)$good['new'] ? 'Да' : 'Нет ' ?></span>
                    <a href="/admin/goods/add?id=<?= $good['id'] ?>" class="product-item__edit"
                       aria-label="Редактировать"></a>
                    <a href="/admin/goods/delete?id=<?=$good['id'];?>" class="product-item__delete"></a>
                </li>
            <? endforeach; ?>
        </ul>
        <br><br><br>
        <? printPagination($p, $maxPage); ?>
    <? else : ?>
        <h2>Не найдено ни одного товара</h2>
    <? endif; ?>
</main>
