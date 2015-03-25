<?php

/**
 * articleBackendController контроллер для работы с новостями в панели управления
 *
 * @author    yupe team <team@yupe.ru>
 * @link      http://yupe.ru
 * @copyright 2009-2013 amyLabs && Yupe! team
 * @package   yupe.modules.article.controllers
 * @version   0.6
 *
 */
class ArticleBackendController extends yupe\components\controllers\BackController
{
    public function accessRules()
    {
        return array(
            array('allow', 'roles' => array('admin')),
            array('allow', 'actions' => array('create'), 'roles' => array('article.articleBackend.Create')),
            array('allow', 'actions' => array('delete'), 'roles' => array('article.articleBackend.Delete')),
            array('allow', 'actions' => array('index'), 'roles' => array('article.articleBackend.Index')),
            array('allow', 'actions' => array('inlineEdit'), 'roles' => array('article.articleBackend.Update')),
            array('allow', 'actions' => array('update'), 'roles' => array('article.articleBackend.Update')),
            array('allow', 'actions' => array('view'), 'roles' => array('article.articleBackend.View')),
            array('deny')
        );
    }

    public function actions()
    {
        return array(
            'inline' => array(
                'class'           => 'yupe\components\actions\YInLineEditAction',
                'model'           => 'Article',
                'validAttributes' => array('title', 'alias', 'date', 'status')
            )
        );
    }

    /**
     * Displays a particular model.
     *
     * @param integer $id the ID of the model to be displayed
     *
     * @return void
     */
    public function actionView($id)
    {
        $this->render('view', array('model' => $this->loadModel($id)));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return void
     */
    public function actionCreate()
    {
        $model = new Article();

        if (($data = Yii::app()->getRequest()->getPost('Article')) !== null) {

            $model->setAttributes($data);

            if ($model->save()) {

                Yii::app()->user->setFlash(
                    yupe\widgets\YFlashMessages::SUCCESS_MESSAGE,
                    Yii::t('ArticleModule.article', 'article article was created!')
                );

                $this->redirect(
                    (array)Yii::app()->getRequest()->getPost(
                        'submit-type',
                        array('create')
                    )
                );
            }
        }
        $languages = $this->yupe->getLanguagesList();

        //если добавляем перевод
        $id = (int)Yii::app()->getRequest()->getQuery('id');
        $lang = Yii::app()->getRequest()->getQuery('lang');

        if (!empty($id) && !empty($lang)) {
            $article = Article::model()->findByPk($id);

            if (null === $article) {
                Yii::app()->user->setFlash(
                    yupe\widgets\YFlashMessages::ERROR_MESSAGE,
                    Yii::t('ArticleModule.article', 'Targeting article was not found!')
                );

                $this->redirect(array('/article/articleBackend/create'));
            }

            if (!array_key_exists($lang, $languages)) {
                Yii::app()->user->setFlash(
                    yupe\widgets\YFlashMessages::ERROR_MESSAGE,
                    Yii::t('ArticleModule.article', 'Language was not found!')
                );

                $this->redirect(array('/article/articleBackend/create'));
            }

            Yii::app()->user->setFlash(
                yupe\widgets\YFlashMessages::SUCCESS_MESSAGE,
                Yii::t(
                    'ArticleModule.article',
                    'You inserting translation for {lang} language',
                    array(
                        '{lang}' => $languages[$lang]
                    )
                )
            );
            $model->lang = $lang;
            $model->alias = $article->alias;
            $model->date = $article->date;
            $model->category_id = $article->category_id;
            $model->title = $article->title;
        } else {
            $model->date = date('d-m-Y');
            $model->lang = Yii::app()->language;
        }

        $criteria = new CDbCriteria();

        $criteria->select = new CDbExpression('MAX(sort) as sort');

        $max = $model->find($criteria);

        $model->sort = $max->sort + 1; // Set sort in Adding Form as ma x+ 1

        $this->render('create', array('model' => $model, 'languages' => $languages));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param null $alias
     * @param integer $id the ID of the model to be updated
     *
     * @throws CHttpException
     *
     * @return void
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        if (($data = Yii::app()->getRequest()->getPost('Article')) !== null) {

            $model->setAttributes($data);

            if ($model->save()) {

                Yii::app()->user->setFlash(
                    yupe\widgets\YFlashMessages::SUCCESS_MESSAGE,
                    Yii::t('ArticleModule.article', 'article article was updated!')
                );

                $this->redirect(
                    Yii::app()->getRequest()->getIsPostRequest()
                        ? (array)Yii::app()->getRequest()->getPost(
                        'submit-type',
                        array('update', 'id' => $model->id)
                    )
                        : array('view', 'id' => $model->id)
                );
            }
        }

        // найти по alias страницы на других языках
        $langModels = Article::model()->findAll(
            'alias = :alias AND id != :id',
            array(
                ':alias' => $model->alias,
                ':id'    => $model->id
            )
        );

        $this->render(
            'update',
            array(
                'langModels' => CHtml::listData($langModels, 'lang', 'id'),
                'model'      => $model,
                'languages'  => $this->yupe->getLanguagesList()
            )
        );
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param null $alias
     * @param integer $id the ID of the model to be deleted
     *
     * @throws CHttpException
     *
     * @return void
     */
    public function actionDelete($id = null)
    {
        if (Yii::app()->getRequest()->getIsPostRequest()) {

            $this->loadModel($id)->delete();

            Yii::app()->user->setFlash(
                yupe\widgets\YFlashMessages::SUCCESS_MESSAGE,
                Yii::t('ArticleModule.article', 'Record was removed!')
            );

            // если это AJAX запрос ( кликнули удаление в админском grid view), мы не должны никуда редиректить
            Yii::app()->getRequest()->getParam('ajax') !== null || $this->redirect(
                (array)Yii::app()->getRequest()->getPost('returnUrl', 'index')
            );
        } else {
            throw new CHttpException(
                400,
                Yii::t('ArticleModule.article', 'Bad raquest. Please don\'t use similar requests anymore!')
            );
        }
    }

    /**
     * Manages all models.
     *
     * @return void
     */
    public function actionIndex()
    {
        $model = new Article('search');

        $model->unsetAttributes(); // clear any default values

        $model->setAttributes(
            Yii::app()->getRequest()->getParam(
                'Article',
                array()
            )
        );
        $this->render('index', array('model' => $model));
    }

    public function actionSortable()
    {
        $sortOrder = Yii::app()->request->getPost('sortOrder');

        if (empty($sortOrder)) {
            throw new CHttpException(404);
        }

        if (Article::model()->sort($sortOrder)) {
            Yii::app()->ajax->success();
        }

        Yii::app()->ajax->failure();
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     *
     * @param integer the ID of the model to be loaded
     *
     * @return void
     *
     * @throws CHttpException If record not found
     */
    public function loadModel($id)
    {
        if (($model = Article::model()->findByPk($id)) === null) {
            throw new CHttpException(
                404,
                Yii::t('ArticleModule.article', 'Requested page was not found!')
            );
        }

        return $model;
    }
}
