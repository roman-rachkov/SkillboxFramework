<?php

//$orders = selectAll('orders');

$p = empty($_GET['p']) ? 1 : prepareData($_GET['p']);

$page = ($p - 1) * MAX_RESULTS_PER_PAGE;
$maxPage = round(countRows('orders') / MAX_RESULTS_PER_PAGE);

$sql = "SELECT * FROM orders ORDER by status ASC LIMIT {$page}," . MAX_RESULTS_PER_PAGE;
$orders = sql($sql)->fetchAll(PDO::FETCH_ASSOC);

foreach ($orders as &$order) {
    $order['client'] = getConnectedRows('orders', 'clients', $order['id'])[0];
    $order['goods'] = getConnectedRows('orders', 'goods', $order['id']);
    $order['amount'] = 0;
    foreach ($order['goods'] as $good) {
        $order['amount'] += $good['price'];
    }
    $order['amount'] = ($order['delivery'] == 'delivery' && $order['amount'] < (int)getSetting('minimal_price_free_delivery')) ? $order['amount'] + (int)getSetting('delivery_price') : $order['amount'];
    debug($order);
}

//debug($orders);

return compact('orders', 'p', 'maxPage');

