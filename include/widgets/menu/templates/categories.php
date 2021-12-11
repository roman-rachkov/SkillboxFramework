<ul <?= $class ? 'class="' . $class . '"' : '' ?>>
    <li>
        <a class="filter__list-item <?= !isset($_GET['cat']) || $_GET['cat'] == 'all' ? 'active' : ''; ?>" data-cat="all" href="/">
            Все
        </a>
    </li>
    <?php foreach ($arr as $menuItem): ?>
        <li>
            <a class="filter__list-item <?= isset($_GET['cat']) && $_GET['cat'] == $menuItem['alias'] ? 'active' : ''; ?>" data-cat="<?= $menuItem['alias'] ?>" href="/?cat=<?= $menuItem['alias']; ?>">
                <?= shortString($menuItem['title']); ?>
            </a>
        </li>
    <?php endforeach; ?>

</ul>


