<?php

/**
 * ArticleModule основной класс модуля article
 *
 * @author yupe team <team@yupe.ru>
 * @link http://yupe.ru
 * @copyright 2009-2013 amyLabs && Yupe! team
 * @package yupe.modules.article
 * @since 0.1
 *
 */

use yupe\components\WebModule;

class ArticleModule extends WebModule
{
    const VERSION = '0.9';

    public $uploadPath = 'article';
    public $allowedExtensions = 'jpg,jpeg,png,gif';
    public $minSize = 0;
    public $maxSize = 5368709120;
    public $maxFiles = 1;
    public $rssCount = 10;
    public $perPage = 10;

    public function getDependencies()
    {
        return array(
            'user',
            'category',
        );
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
        $messages = array();

        $uploadPath = Yii::app()->uploadManager->getBasePath() . DIRECTORY_SEPARATOR . $this->uploadPath;

        if (!is_writable($uploadPath)) {
            $messages[WebModule::CHECK_ERROR][] = array(
                'type'    => WebModule::CHECK_ERROR,
                'message' => Yii::t(
                        'ArticleModule.article',
                        'Directory "{dir}" is not accessible for write! {link}',
                        array(
                            '{dir}'  => $uploadPath,
                            '{link}' => CHtml::link(
                                    Yii::t('ArticleModule.article', 'Change settings'),
                                    array(
                                        '/yupe/backend/modulesettings/',
                                        'module' => 'article',
                                    )
                                ),
                        )
                    ),
            );
        }

        return (isset($messages[WebModule::CHECK_ERROR])) ? $messages : true;
    }

    public function getParamsLabels()
    {
        return array(
            'mainCategory'      => Yii::t('ArticleModule.article', 'Main article category'),
            'adminMenuOrder'    => Yii::t('ArticleModule.article', 'Menu items order'),
            'editor'            => Yii::t('ArticleModule.article', 'Visual Editor'),
            'uploadPath'        => Yii::t(
                    'ArticleModule.article',
                    'Uploading files catalog (relatively {path})',
                    array(
                        '{path}' => Yii::getPathOfAlias('webroot') . DIRECTORY_SEPARATOR . Yii::app()->getModule(
                                "yupe"
                            )->uploadPath
                    )
                ),
            'allowedExtensions' => Yii::t('ArticleModule.article', 'Accepted extensions (separated by comma)'),
            'minSize'           => Yii::t('ArticleModule.article', 'Minimum size (in bytes)'),
            'maxSize'           => Yii::t('ArticleModule.article', 'Maximum size (in bytes)'),
            'rssCount'          => Yii::t('ArticleModule.article', 'RSS records'),
            'perPage'           => Yii::t('ArticleModule.article', 'article per page')
        );
    }

    public function getEditableParams()
    {
        return array(
            'adminMenuOrder',
            'editor'       => Yii::app()->getModule('yupe')->getEditors(),
            'mainCategory' => CHtml::listData($this->getCategoryList(), 'id', 'name'),
            'uploadPath',
            'allowedExtensions',
            'minSize',
            'maxSize',
            'rssCount',
            'perPage'
        );
    }

    public function getEditableParamsGroups()
    {
        return array(
            'main'   => array(
                'label' => Yii::t('ArticleModule.article', 'General module settings'),
                'items' => array(
                    'adminMenuOrder',
                    'editor',
                    'mainCategory'
                )
            ),
            'images' => array(
                'label' => Yii::t('ArticleModule.article', 'Images settings'),
                'items' => array(
                    'uploadPath',
                    'allowedExtensions',
                    'minSize',
                    'maxSize'
                )
            ),
            'list'   => array(
                'label' => Yii::t('ArticleModule.article', 'article lists'),
                'items' => array(
                    'rssCount',
                    'perPage'
                )
            ),
        );
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
        return Yii::t('ArticleModule.article', 'yupe team');
    }

    public function getAuthorEmail()
    {
        return Yii::t('ArticleModule.article', 'team@yupe.ru');
    }

    public function getUrl()
    {
        return Yii::t('ArticleModule.article', 'http://yupe.ru');
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
        return array(
            array(
                'icon'  => 'fa fa-fw fa-list-alt',
                'label' => Yii::t('ArticleModule.article', 'article list'),
                'url'   => array('/article/articleBackend/index')
            ),
            array(
                'icon'  => 'fa fa-fw fa-plus-square',
                'label' => Yii::t('ArticleModule.article', 'Create article'),
                'url'   => array('/article/articleBackend/create')
            ),
            array(
                'icon'  => 'fa fa-fw fa-folder-open',
                'label' => Yii::t('ArticleModule.article', 'article categories'),
                'url'   => array('/category/categoryBackend/index', 'Category[parent_id]' => (int)$this->mainCategory)
            ),
        );
    }

    public function isMultiLang()
    {
        return true;
    }

    public function init()
    {
        parent::init();

        $this->setImport(
            array(
                'article.models.*'
            )
        );
    }

    public function getAuthItems()
    {
        return array(
            array(
                'name'        => 'article.articleManager',
                'description' => Yii::t('ArticleModule.article', 'Manage article'),
                'type'        => AuthItem::TYPE_TASK,
                'items'       => array(
                    array(
                        'type'        => AuthItem::TYPE_OPERATION,
                        'name'        => 'article.articleBackend.Create',
                        'description' => Yii::t('ArticleModule.article', 'Creating article')
                    ),
                    array(
                        'type'        => AuthItem::TYPE_OPERATION,
                        'name'        => 'article.articleBackend.Delete',
                        'description' => Yii::t('ArticleModule.article', 'Removing article')
                    ),
                    array(
                        'type'        => AuthItem::TYPE_OPERATION,
                        'name'        => 'article.articleBackend.Index',
                        'description' => Yii::t('ArticleModule.article', 'List of article')
                    ),
                    array(
                        'type'        => AuthItem::TYPE_OPERATION,
                        'name'        => 'article.articleBackend.Update',
                        'description' => Yii::t('ArticleModule.article', 'Editing article')
                    ),
                    array(
                        'type'        => AuthItem::TYPE_OPERATION,
                        'name'        => 'article.articleBackend.Inline',
                        'description' => Yii::t('ArticleModule.article', 'Editing article')
                    ),
                    array(
                        'type'        => AuthItem::TYPE_OPERATION,
                        'name'        => 'article.articleBackend.View',
                        'description' => Yii::t('ArticleModule.article', 'Viewing article')
                    ),
                )
            )
        );
    }
}
