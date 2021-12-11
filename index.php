<?php

require_once 'include/include.php';

//global $menuArray;

if (mb_stripos(getPath(), 'admin') === false) {
    $menuArray = decodeData(sql("SELECT * FROM menu WHERE path NOT LIKE '%admin%'")->fetchAll(PDO::FETCH_ASSOC));
} else {
    $menuArray = decodeData(sql("SELECT * FROM menu WHERE path LIKE '%admin%'")->fetchAll(PDO::FETCH_ASSOC));

}
$menuArray = arraySort($menuArray, 'sort');

$content = getController($route);
//debug(get_defined_vars());
debug(['route' => $route]);

getWidgets();

require_once INCLUDE_PATH . 'templates/header.php';


$page = getTemplate($route, $content);
echo $page['page'];

debug(['getFullRequest' => getFullRequest(), 'getUri' => getUri(), 'getPath' => getPath()]);
require_once INCLUDE_PATH . 'templates/footer.php';



