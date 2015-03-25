<?php

return array(
    'module'    => array(
        'class' => 'application.modules.article.ArticleModule',
    ),
    'import'    => array(),
    'component' => array(),
    'rules'     => array(
        '/article/'        => 'article/article/index',
        '/article/<alias>' => 'article/article/show',
    ),
);
