<main class="page-authorization">
    <h1 class="h h--1">Авторизация</h1>
    <form class="custom-form" action="<?= getPath(); ?>" method="post">
        <input type="email" class="custom-form__input" required="" name="name" value="<?=isset($data)? $data['name'] : ''?>">
        <input type="password" class="custom-form__input" required="" name="password">
        <button class="button" type="submit" name="login">Войти в личный кабинет</button>
    </form>
</main>
