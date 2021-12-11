<?php

if (!isset($_SESSION['user']) && getPath() != '/admin/login') {
    redirect('/admin/login');
} else if(getPath() == '/admin/login' && isset($_SESSION['user'])) {
    redirect('/admin');
} else if(isset($_SESSION['user'])){
    global $menuArray;

    $reqLvl = 0;

    foreach ($menuArray as $item){
        if(false !== strripos(getPath(), $item['path'])){
            $reqLvl = $item['required_lvl'];
        }
    }

    if($reqLvl != 0 && !in_array($reqLvl, array_column($_SESSION['user']['groups'], 'id'))){
        redirect('/');
    }
};
