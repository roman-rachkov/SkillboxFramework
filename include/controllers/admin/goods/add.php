<?php

if (isAjax()) {
    $post = prepareData($_POST);

    if (isset($post['id'])) {
        $id = $post['id'];
        $stmt = update('goods', ['new' => (int)filter_var($post['new'], FILTER_VALIDATE_BOOLEAN), 'sale' => (int)filter_var($post['sale'], FILTER_VALIDATE_BOOLEAN), 'title' => $post['product-name'], 'price' => (int)$post['product-price']], ['id' => $id]);
    } else {
        $stmt = insert('goods', ['new' => (int)filter_var($post['new'], FILTER_VALIDATE_BOOLEAN), 'sale' => (int)filter_var($post['sale'], FILTER_VALIDATE_BOOLEAN), 'title' => $post['product-name'], 'price' => (int)$post['product-price'], 'photo' => '']);
        $id = tableLastID('goods');
    }
    if (boolval(intval($stmt->errorCode()))) {
        die(json_encode(['success' => false]));
    }

    delete('goods_categories', ['goods_id' => $id]);

    if (!empty($post['category'])) {
        $cats = explode(',', $post['category']);
        foreach ($cats as $cat) {
            insert('goods_categories', ['goods_id' => $id, 'categories_id' => $cat]);
        }
    }
    if (!empty($_FILES)) {
        $name = 'good-' . $id;
        $files = uploadImages($_FILES, $name, 'goods');
        if (!empty($files['errors'])) {
            die(json_encode(['success' => false]));
        }
        $stmt = update('goods', ['photo' => $name . '.' . explode('.', $files['files'][0])[1]], ['id' => $id]);
        if (boolval(intval($stmt->errorCode()))) {
            die(json_encode(['success' => false]));
        }
    }


    die(json_encode(['success' => true]));
}

if (!empty($_GET['id'])) {

    $good = selectColumnsWhere('goods', ['id' => $_GET['id']], '*', null, false);
    $good['categories'] = getConnectedRows('goods', 'categories', $good['id']);

//    $goodCategories = sql('SELECT categories.* FROM categories JOIN goods_categories on goods_categories.goods_id = '.$good['id'].' WHERE categories.id = goods_categories.categories_id')->fetchAll(PDO::FETCH_ASSOC);


    debug($good);
}
$categories = selectAll('categories');

return compact('categories', 'good');