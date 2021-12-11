<?php

if (isAjax()) {
    debug(getallheaders());

    $post = prepareData($_POST);

    if (empty($post['name']) || empty($post['surname']) || empty($post['phone']) || empty($post['email'])) {
        die(json_encode(['data' => 1, 'success' => false]));
    } else if ($post['delivery'] == 'dev-yes' && (empty($post['city']) || empty($post['street']) || empty($post['home']) || empty($post['aprt']))) {
        die(json_encode(['data' => $post, 'success' => false]));
    }

    $good = selectColumnsWhere('goods', ['id' => $post['id']], '*', '0,1', false);

    $client['name'] = $post['name'];
    $client['surname'] = $post['surname'];
    $client['thirdname'] = empty($post['thirdName']) ? '' : $post['thirdName'];
    $client['email'] = $post['email'];
    $client['phone'] = (int)$post['phone'];
    $client['adress'] = $post['delivery'] == 'dev-yes' ? "г. {$post['city']}, ул. {$post['street']}, д.{$post['home']}, кв. {$post['aprt']}" : '';

    $stmt = insert('clients', $client);
    if(boolval(intval($stmt->errorCode()))){
        die(json_encode(['success' => false]));
    }
    $clientId = tableLastID('clients');

    $order['delivery'] = $post['delivery'] == 'dev-yes' ? 'delivery' : 'pickup';
    $order['payment'] = $post['pay'] =='card' ? 'card' : 'cash';
    $order['comment'] = empty($post['comment']) ? '' : $post['comment'];

    $stmt = insert('orders', $order);
    if(boolval(intval($stmt->errorCode()))){
        die(json_encode(['success' => false]));
    }
    $orderId = tableLastID('orders');

    $stmt = insert('orders_goods', ['orders_id' => $orderId, 'goods_id' => $post['id']]);
    if(boolval(intval($stmt->errorCode()))){
        die(json_encode(['success' => false]));
    }
    $stmt = insert('orders_clients', ['orders_id' => $orderId, 'clients_id' => $clientId]);
    if(boolval(intval($stmt->errorCode()))){
        die(json_encode(['success' => false]));
    }

    die(json_encode(['success' => true]));
}

redirect('/');