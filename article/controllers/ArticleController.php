<?php

/**
 * ArticleController контроллер для работы с новостями в публичной части сайта
 *
 * @author yupe team <team@yupe.ru>
 * @link http://yupe.ru
 * @copyright 2009-2013 amyLabs && Yupe! team
 * @package yupe.modules.Article.controllers
 * @since 0.1
 *
 */
class ArticleController extends yupe\components\controllers\FrontController
{
    public function actionShow($alias)
    {
        $article = Article::model()->published();

        $article = ($this->isMultilang())
            ? $article->language(Yii::app()->language)->find('alias = :alias', array(':alias' => $alias))
            : $article->find('alias = :alias', array(':alias' => $alias));

        if (!$article) {
            throw new CHttpException(404, Yii::t('ArticleModule.article', 'Article article was not found!'));
        }

        // проверим что пользователь может просматривать эту новость
        if ($article->is_protected == Article::PROTECTED_YES && !Yii::app()->user->isAuthenticated()) {
            Yii::app()->user->setFlash(
                yupe\widgets\YFlashMessages::ERROR_MESSAGE,
                Yii::t('ArticleModule.article', 'You must be an authorized user for view this page!')
            );

            $this->redirect(array(Yii::app()->getModule('user')->accountActivationSuccess));
        }

        $this->render('show', array('article' => $article));
    }

    public function actionIndex()
    {
        $dbCriteria = new CDbCriteria(array(
            'condition' => 't.status = :status',
            'params'    => array(
                ':status' => Article::STATUS_PUBLISHED,
            ),
            'limit'     => $this->module->perPage,
            'order'     => 't.creation_date DESC',
            'with'      => array('user'),
        ));

        if (!Yii::app()->user->isAuthenticated()) {
            $dbCriteria->mergeWith(
                array(
                    'condition' => 'is_protected = :is_protected',
                    'params'    => array(
                        ':is_protected' => Article::PROTECTED_NO
                    )
                )
            );
        }

        if ($this->isMultilang()) {
            $dbCriteria->mergeWith(
                array(
                    'condition' => 't.lang = :lang',
                    'params'    => array(':lang' => Yii::app()->language),
                )
            );
        }

        $dataProvider = new CActiveDataProvider('Article', array(
            'criteria' => $dbCriteria,
            'pagination'=>array(
                'pageSize'=>15,
            ),
        ));

        $category = \Category::model()->findByAttributes( array('alias' => 'stranica-stati'));

        $this->render('index', array(
            'dataProvider' => $dataProvider,
            'category' => $category,
        ));
    }
}
