<?php

/**
 * ArticleModule основной класс модуля article
 *
 * @author Chief88 <serg.latyshkov@gmail.com>
 * @link https://github.com/Chief88/yupe-module-article
 *
 * @package yupe.modules.article
 *
 *
 */

use yupe\components\WebModule;

class ArticleModule extends WebModule
{
    const VERSION = '0.9.6';

    public $uploadPath = 'article';
    public $allowedExtensions = 'jpg,jpeg,png,gif';
    public $minSize = 0;
    public $maxSize = 5368709120;
    public $maxFiles = 1;
    public $rssCount = 10;
    public $perPage = 10;

    public function getDependencies()
    {
        return [
            'user',
            'category',
        ];
    }

    public function getInstall()
    {
        if (parent::getInstall()) {
            @mkdir(Yii::app()->uploadManager->getBasePath() . DIRECTORY_SEPARATOR . $this->uploadPath, 0777);
        }

        return false;
    }

    public function checkSelf()
    {
        $messages = [];

        $uploadPath = Yii::app()->uploadManager->getBasePath() . DIRECTORY_SEPARATOR . $this->uploadPath;

        if (!is_writable($uploadPath)) {
            $messages[WebModule::CHECK_ERROR][] = [
                'type'    => WebModule::CHECK_ERROR,
                'message' => Yii::t(
                        'ArticleModule.article',
                        'Directory "{dir}" is not accessible for write! {link}',
                        [
                            '{dir}'  => $uploadPath,
                            '{link}' => CHtml::link(
                                    Yii::t('ArticleModule.article', 'Change settings'),
                                    [
                                        '/yupe/backend/modulesettings/',
                                        'module' => 'article',
                                    ]
                                ),
                        ]
                    ),
            ];
        }

        return (isset($messages[WebModule::CHECK_ERROR])) ? $messages : true;
    }

    public function getParamsLabels()
    {
        return [
            'mainCategory'      => Yii::t('ArticleModule.article', 'Main article category'),
            'adminMenuOrder'    => Yii::t('ArticleModule.article', 'Menu items order'),
            'editor'            => Yii::t('ArticleModule.article', 'Visual Editor'),
            'uploadPath'        => Yii::t(
                    'ArticleModule.article',
                    'Uploading files catalog (relatively {path})',
                    [
                        '{path}' => Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . Yii::app()->getModule(
                                "yupe"
                            )->uploadPath
                    ]
                ),
            'allowedExtensions' => Yii::t('ArticleModule.article', 'Accepted extensions (separated by comma)'),
            'minSize'           => Yii::t('ArticleModule.article', 'Minimum size (in bytes)'),
            'maxSize'           => Yii::t('ArticleModule.article', 'Maximum size (in bytes)'),
            'rssCount'          => Yii::t('ArticleModule.article', 'RSS records'),
            'perPage'           => Yii::t('ArticleModule.article', 'article per page')
        ];
    }

    public function getEditableParams()
    {
        return [
            'adminMenuOrder',
            'editor'       => Yii::app()->getModule('yupe')->getEditors(),
            'mainCategory' => CHtml::listData($this->getCategoryList(), 'id', 'name'),
            'uploadPath',
            'allowedExtensions',
            'minSize',
            'maxSize',
            'rssCount',
            'perPage'
        ];
    }

    public function getEditableParamsGroups()
    {
        return [
            'main'   => [
                'label' => Yii::t('ArticleModule.article', 'General module settings'),
                'items' => [
                    'adminMenuOrder',
                    'editor',
                    'mainCategory'
                ]
            ],
            'images' => [
                'label' => Yii::t('ArticleModule.article', 'Images settings'),
                'items' => [
                    'uploadPath',
                    'allowedExtensions',
                    'minSize',
                    'maxSize'
                ]
            ],
            'list'   => [
                'label' => Yii::t('ArticleModule.article', 'article lists'),
                'items' => [
                    'rssCount',
                    'perPage'
                ]
            ],
        ];
    }

    public function getVersion()
    {
        return self::VERSION;
    }

    public function getIsInstallDefault()
    {
        return true;
    }

    public function getCategory()
    {
        return Yii::t('ArticleModule.article', 'Content');
    }

    public function getName()
    {
        return Yii::t('ArticleModule.article', 'article');
    }

    public function getDescription()
    {
        return Yii::t('ArticleModule.article', 'Module for creating and management article');
    }

    public function getAuthor()
    {
        return Yii::t('ArticleModule.article', 'Chief88');
    }

    public function getAuthorEmail()
    {
        return Yii::t('ArticleModule.article', 'serg.latyshkov@gmail.com');
    }

    public function getUrl()
    {
        return Yii::t('ArticleModule.article', 'https://github.com/Chief88/yupe-module-article');
    }

    public function getIcon()
    {
        return "fa fa-fw fa-bullhorn";
    }

    public function getAdminPageLink()
    {
        return '/article/articleBackend/index';
    }

    public function getNavigation()
    {
        return [
            [
                'icon'  => 'fa fa-fw fa-list-alt',
                'label' => Yii::t('ArticleModule.article', 'article list'),
                'url'   => ['/article/articleBackend/index']
            ],
            [
                'icon'  => 'fa fa-fw fa-plus-square',
                'label' => Yii::t('ArticleModule.article', 'Create article'),
                'url'   => ['/article/articleBackend/create']
            ],
        ];
    }

    public function isMultiLang()
    {
        return true;
    }

    public function init()
    {
        parent::init();

        $this->setImport(
            [
                'article.models.*'
            ]
        );
    }

    public function getAuthItems()
    {
        return [
            [
                'name'        => 'article.articleManager',
                'description' => Yii::t('ArticleModule.article', 'Manage article'),
                'type'        => AuthItem::TYPE_TASK,
                'items'       => [
                    [
                        'type'        => AuthItem::TYPE_OPERATION,
                        'name'        => 'article.articleBackend.Create',
                        'description' => Yii::t('ArticleModule.article', 'Creating article')
                    ],
                    [
                        'type'        => AuthItem::TYPE_OPERATION,
                        'name'        => 'article.articleBackend.Delete',
                        'description' => Yii::t('ArticleModule.article', 'Removing article')
                    ],
                    [
                        'type'        => AuthItem::TYPE_OPERATION,
                        'name'        => 'article.articleBackend.Index',
                        'description' => Yii::t('ArticleModule.article', 'List of article')
                    ],
                    [
                        'type'        => AuthItem::TYPE_OPERATION,
                        'name'        => 'article.articleBackend.Update',
                        'description' => Yii::t('ArticleModule.article', 'Editing article')
                    ],
                    [
                        'type'        => AuthItem::TYPE_OPERATION,
                        'name'        => 'article.articleBackend.Inline',
                        'description' => Yii::t('ArticleModule.article', 'Editing article')
                    ],
                    [
                        'type'        => AuthItem::TYPE_OPERATION,
                        'name'        => 'article.articleBackend.View',
                        'description' => Yii::t('ArticleModule.article', 'Viewing article')
                    ],
                ]
            ]
        ];
    }
}
