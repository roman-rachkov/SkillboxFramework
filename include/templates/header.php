<?php

if (!isAjax()) :

if(isset($_SESSION['user'])){
    debug($_SESSION['user']);
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Fashion</title>

    <meta name="description" content="Fashion - интернет-магазин">
    <meta name="keywords" content="Fashion, интернет-магазин, одежда, аксессуары">

    <meta name="theme-color" content="#393939">

    <link rel="preload" href="/static/img/intro/coats-2018.jpg" as="image">
    <link rel="preload" href="/static/fonts/opensans-400-normal.woff2" as="font">
    <link rel="preload" href="/static/fonts/roboto-400-normal.woff2" as="font">
    <link rel="preload" href="/static/fonts/roboto-700-normal.woff2" as="font">

    <link rel="icon" href="/static/img/favicon.png">
    <link rel="stylesheet" href="/static/css/style.css">

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="/static/js/scripts.js" defer=""></script>
</head>
<body>
<header class="page-header">
    <a class="page-header__logo" href="/">
        <img src="/static/img/logo.svg" alt="Fashion">
    </a>
    <nav class="page-header__menu">

        <? printMenu($menuArray,'menu', 'main-menu main-menu--header'); ?>
    </nav>

</header>


<?php
endif;
?>
