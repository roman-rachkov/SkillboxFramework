<?php

$p = empty($_GET['p']) ? 1 : prepareData($_GET['p']);

$page = ($p - 1) * MAX_RESULTS_PER_PAGE;
$maxPage = round(countRows('goods') / MAX_RESULTS_PER_PAGE);
debug($maxPage);
$sql = "SELECT * FROM goods LIMIT ".$page.','.MAX_RESULTS_PER_PAGE;

$goods = sql($sql)->fetchAll(PDO::FETCH_ASSOC);

return compact('goods', 'p', 'maxPage');