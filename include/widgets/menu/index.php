<?php

function printMenu($arr, $template = 'default', $class = '', $indent = 0)
{
    require __DIR__ . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $template . '.php';
}


/**
 * Строит дерево из элементов по ключу
 * @param $arr
 * @param string $key
 * @return array
 */
function buildTree($arr, $key = 'parent_id')
{
    $arr = buildArray($arr);
    $tree = [];
    foreach ($arr as $id => &$value) {
        if (empty($value[$key])) {
            $tree[$id] = &$value;
        } else {
            $arr[$value[$key]]['childs'][$id] = &$value;
        }
    }
    return $tree;
}

/**
 * Приводит массив к правильному соотношению id => эллемент
 * @param $arr
 * @return array
 */
function buildArray($arr)
{
    $res = [];
    foreach ($arr as $key => $value) {
        $res[$value['id']] = $value;
    }
    return $res;
}