<?php

if(!empty($_GET['id'])){
    $id = prepareData($_GET['id']);

    $photo = selectColumnsWhere('goods', ['id' => $id], 'photo', '0,1', false)['photo'];

    $path = UPLOAD_PATH.'goods/'.$photo;

    if(file_exists($path)){
        unlink($path);
    }
    delete('goods', ['id' => $id]);

    redirect($_SERVER['HTTP_REFERER']);
}

setError('Что то пошло не так');
redirect('/admin');