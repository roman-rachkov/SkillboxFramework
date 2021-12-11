<ul class="shop__paginator paginator">
    <?php if ($page - 2 >= 1): ?>
        <li><a class="paginator__item" href="<?= getPath() . '?' . htmlspecialchars(http_build_query(array_merge($_GET, ['p' => 1]))) ?>">1</a></li>
        <?php if ($page - 2 > 1): ?>
            <li>...</li><?php endif; endif; ?>
    <?php if ($page - 1 >= 1): ?>
        <li><a class="paginator__item" href="<?= getPath() . '?' . htmlspecialchars(http_build_query(array_merge($_GET, ['p' => $page - 1]))) ?>"><?= $page - 1 ?></a></li>
    <?php endif; ?>
    <li><a class="paginator__item"><?= $page ?></a></li>
    <?php if ($page + 1 <= $maxPage): ?>
        <li><a class="paginator__item" href="<?= getPath() . '?' . htmlspecialchars(http_build_query(array_merge($_GET, ['p' => $page + 1]))) ?>"><?= $page + 1 ?></a></li>
    <?php endif; ?>

    <?php if ($page + 2 <= $maxPage): ?>
        <? if ($page + 2 < $maxPage): ?>
            <li>...</li><? endif; ?>
        <li ><a class="paginator__item" href="<?= getPath() . '?' . htmlspecialchars(http_build_query(array_merge($_GET, ['p' => $maxPage]))) ?>"><?= $maxPage ?></a></li>
    <?php endif; ?>
</ul>