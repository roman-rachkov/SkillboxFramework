<?php

if(!empty($_POST)){
    $post = prepareData($_POST);

    foreach ($post as $key=>$value){
        update('settings', ['setting' => $value], ['name' => $key]);
    }
}

return;