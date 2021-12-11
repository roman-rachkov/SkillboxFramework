<?php

function printPagination($page, $maxPage, $template = 'default')
{
    if ($maxPage > 1) {
        require 'templates/' . $template . '.php';
    }
}