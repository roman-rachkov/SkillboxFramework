<?php

if(isAjax()){
    $id = prepareData($_POST['id']);

    $order = selectColumnsWhere('orders', ['id' => $id], '*', '0,1', false);

    $status = !(bool)$order['status'];
    debug($status);
    $stmt = update('orders', ['status' => (int)$status], ['id'=>$id]);
    if(boolval(intval($stmt->errorCode()))){
        die(json_encode(['success' => false]));
    }
    die(json_encode(['success' => true]));
}

redirect('/');