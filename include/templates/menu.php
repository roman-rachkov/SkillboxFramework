<ul <?= $class ? 'class="' . $class . '"' : '' ?>>

    <?php foreach ($menuArray as $menuItem): ?>
        <? if ($menuItem['required_lvl'] == 0 || (isset($_SESSION['user']) && in_array($menuItem['required_lvl'], array_column($_SESSION['user']['groups'], 'id')))): ?>
            <li>
                <a class="main-menu__item <?= getFullRequest() == $menuItem['path'] ? 'active' : ''; ?>" href="<?= $menuItem['path']; ?>">
                    <?= shortString($menuItem['title']); ?>
                </a>
            </li>
        <? endif; ?>
    <?php endforeach; ?>

</ul>


