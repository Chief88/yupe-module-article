<?php

/**
 * ArticleController
 *
 * @author Chief88 <serg.latyshkov@gmail.com>
 * @link https://github.com/Chief88/yupe-module-article
 *
 * @package yupe.modules.Article.controllers
 *
 *
 */
class ArticleController extends yupe\components\controllers\FrontController
{
    public function actionShow($slug)
    {
        $article = Article::model()->published();

        $article = ($this->isMultilang())
            ? $article->language(Yii::app()->language)->find('slug = :slug', [':slug' => $slug])
            : $article->find('slug = :slug', [':slug' => $slug]);

        if (!$article) {
            throw new CHttpException(404, Yii::t('ArticleModule.article', 'Article article was not found!'));
        }

        // проверим что пользователь может просматривать эту новость
        if ($article->is_protected == Article::PROTECTED_YES && !Yii::app()->user->isAuthenticated()) {
            Yii::app()->user->setFlash(
                yupe\widgets\YFlashMessages::ERROR_MESSAGE,
                Yii::t('ArticleModule.article', 'You must be an authorized user for view this page!')
            );

            $this->redirect([Yii::app()->getModule('user')->accountActivationSuccess]);
        }

        $this->render('show', ['article' => $article]);
    }

    public function actionIndex()
    {
        $dbCriteria = new CDbCriteria([
            'condition' => 't.status = :status',
            'params'    => [
                ':status' => Article::STATUS_PUBLISHED,
            ],
            'limit'     => $this->module->perPage,
            'order'     => 't.creation_date DESC',
            'with'      => ['user'],
        ]);

        if (!Yii::app()->user->isAuthenticated()) {
            $dbCriteria->mergeWith(
                [
                    'condition' => 'is_protected = :is_protected',
                    'params'    => [
                        ':is_protected' => Article::PROTECTED_NO
                    ]
                ]
            );
        }

        if ($this->isMultilang()) {
            $dbCriteria->mergeWith(
                [
                    'condition' => 't.lang = :lang',
                    'params'    => [':lang' => Yii::app()->language],
                ]
            );
        }

        $dataProvider = new CActiveDataProvider('Article', [
            'criteria' => $dbCriteria,
            'pagination'=>[
                'pageSize'=>15,
            ],
        ]);

        $category = \Category::model()->findByAttributes( ['slug' => 'poleznye-stati-i-rukovodstva']);

        $this->render('index', [
            'dataProvider' => $dataProvider,
            'categoryModel' => $category,
        ]);
    }
}
