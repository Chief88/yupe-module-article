<?php

return [
    'module'    => [
        'class' => 'application.modules.article.ArticleModule',
    ],
    'import'    => [],
    'component' => [],
    'rules'     => [
        '/article/'        => 'article/article/index',
        '/article/<slug>' => 'article/article/show',
    ],
];
