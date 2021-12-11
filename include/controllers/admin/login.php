<?php

if (isset($_POST['login'])) {


    $data = prepareData($_POST);

    $user = selectColumnsWhere('users', ['email' => $data['name']], '*', '0,1', false);
    if(!empty($user)) {
        if(password_verify($data['password'], $user['password'])){
            $user['groups'] = getConnectedRows('users', 'groups', $user['id']);//selectColumnsWhere('users_groups', ['users_id' => $user['id']]);
            $_SESSION['user'] = $user;
            redirect('/admin');
        } else{
            setError('Неверный пароль');
        }
    } else{
        setError('Пользователь не найден');
    }

    return compact('data');

}