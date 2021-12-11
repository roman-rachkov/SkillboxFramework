<?php

return [
    '^admin/goods$' => [
        'prefix'=> 'admin/goods',
        'controller' => 'index'
    ],
    '^admin/goods/?(?<controller>[a-z0-9]+)?$'=>[
        'prefix' => 'admin/goods',
    ],
    '^admin$' => [
        'prefix' => 'admin',
        'controller' => 'index'
    ],
    '^admin/?(?P<controller>[a-z0-9-]+)?$' => [
        'prefix' => 'admin',
    ],
    '^$' => [
        'controller'=>'index'
    ],
    '^(?P<controller>[a-z0-9-]+)$' => [
    ]
];