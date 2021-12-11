<?php

$get = prepareData($_GET);
$data = [];
$sql = 'FROM goods join categories ';
if (isset($get['cat']) && $get['cat'] != 'all') {
    $sql .= 'on categories.alias = :cat ';
    $data[':cat'] = $get['cat'];
}
$get['order'] = isset($get['order']) ? $get['order'] : 'price';
$get['sort'] = isset($get['sort']) ? $get['sort'] : 'asc';

if (preg_match('/[^a-z0-1_]/i', $get['order'])) {
    setError('Хаха, не плохо!');
    redirect('/');
}
if (!preg_match('/^(asc|desc)$/i', $get['sort'])) {
    setError('Хаха, не плохо!');
    redirect('/');
}

$sql .= "JOIN goods_categories ON goods_categories.categories_id = categories.id WHERE goods.id = goods_categories.goods_id ";

if (isset($get['minPrice'])) {
    $sql .= 'AND goods.price >= :minPrice ';
    $data[':minPrice'] = (int)$get['minPrice'];
}
if (isset($get['maxPrice'])) {
    $sql .= 'AND goods.price <= :maxPrice ';
    $data[':maxPrice'] = (int)$get['maxPrice'];
}
if (isset($get['new'])) {
    $get['new'] = boolval((int)filter_var($get['new'], FILTER_VALIDATE_BOOLEAN));
    if ($get['new']) {
        $sql .= 'AND goods.new = 1 ';
    }
}
if (isset($get['sale'])) {
    $get['sale'] = boolval((int)filter_var($get['sale'], FILTER_VALIDATE_BOOLEAN));
    if ($get['sale']) {
        $sql .= 'AND goods.sale = 1 ';
    }
}

$length = 'SELECT COUNT(*) as length ' . $sql;
$length = (int)sql($length, $data)->fetch(PDO::FETCH_ASSOC)['length'];


$sql = 'SELECT goods.* ' . $sql;
$sql .= "GROUP by goods.id ORDER by goods.{$get['order']} {$get['sort']}";

$get['p'] = isset($get['p']) ? $get['p'] : '1';

if (!preg_match('/^[0-9]+$/i', $get['p'])) {
    setError('Хаха, не плохо!');
    redirect('/');
}
$get['p'] = (int)$get['p'];

$page = $get['p'] - 1 != 0 ? ($get['p']-1) * MAX_RESULTS_PER_PAGE : 0;
$maxPage = floor($length / MAX_RESULTS_PER_PAGE);

$sql .= ' LIMIT ' . $page . ',' . MAX_RESULTS_PER_PAGE;

$goods = sql($sql, $data)->fetchAll(PDO::FETCH_ASSOC);
$categories = selectAll('categories');
//debug($categories);
return compact('get', 'goods', 'maxPage', 'categories');
