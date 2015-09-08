<?php

/**
 * ArticleController
 *
 * @author Chief88 <serg.latyshkov@gmail.com>
 * @link https://github.com/Chief88/yupe-module-article
 * @package yupe.modules.Article.controllers
 *
 */
class ArticleController extends yupe\components\controllers\FrontController
{
    /**
     * Отображение страницы "Пляж"
     */
    public function actionBeach(){
        $dataProvider = $this->_getArticlesByCategory('plyazh', 999);

        $categoryPage = \Category::model()->findByAttributes( ['slug' => 'stranica-plyazh']);

        $this->render('beach', [
            'dataProvider'  => $dataProvider,
            'categoryModel' => $categoryPage,
        ]);
    }

    /**
     * Отображение страницы "Подготовка к школе"
     */
    public function actionPreparationForSchool(){
        $dataProvider = $this->_getArticlesByCategory('podgotovka-k-shkole', 999);

        $categoryPage = \Category::model()->findByAttributes( ['slug' => 'stranica-podgotovka-k-shkole']);

        $this->render('preparationForSchool', [
            'dataProvider'  => $dataProvider,
            'categoryModel' => $categoryPage,
        ]);
    }

    /**
     * Отображение страницы "Мама и малыш"
     */
    public function actionMotherAndBaby(){
        $dataProvider = $this->_getArticlesByCategory('mama-i-malysh-article', 999);

        $categoryPage = \Category::model()->findByAttributes( ['slug' => 'stranica-mama-i-malysh']);

        $this->render('motherAndBaby', [
            'dataProvider'  => $dataProvider,
            'categoryModel' => $categoryPage,
        ]);
    }

    /**
     * Отображение страницы "Детский лагерь"
     */
    public function actionChildrenCamp(){
        $dataProvider = $this->_getArticlesByCategory('detskiy-lager', 999);

        $categoryPage = \Category::model()->findByAttributes( ['slug' => 'stranica-detskiy-lager']);

        $this->render('childrenCamp', [
            'dataProvider'  => $dataProvider,
            'categoryModel' => $categoryPage,
        ]);
    }

    /**
     * Отображение страницы "Грудничковое плавание"
     */
    public function actionInfantSwimming(){
        $dataProvider = $this->_getArticlesByCategory('grudnichkovoe-plavanie', 999);

        $categoryPage = \Category::model()->findByAttributes( ['slug' => 'stranica-grudnichkovoe-plavanie']);

        $this->render('infantSwimming', [
            'dataProvider'  => $dataProvider,
            'categoryModel' => $categoryPage,
        ]);
    }

    public function actionGym(){
        $dataProvider = $this->_getArticlesByCategory('trenazhernyy-zal', 999);

        $categoryPage = \Category::model()->findByAttributes( ['slug' => 'stranica-trenazhernyy-zal']);

        $this->render('gym', [
            'dataProvider'  => $dataProvider,
            'categoryModel' => $categoryPage,
        ]);
    }

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

    public function actionIndex(){
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

    private function _getArticlesByCategory($categorySlug, $pageSize = false){

        if(!$pageSize){
            $pageSize = $this->module->perPage;
        }

        $dbCriteria = new CDbCriteria();
        $dbCriteria->with[] = 'user';
        $dbCriteria->with[] = 'category';
        $dbCriteria->condition = 't.status = :status AND category.slug = :categorySlug';
        $dbCriteria->params = [
            ':status' => Article::STATUS_PUBLISHED,
            ':categorySlug' => $categorySlug,
        ];

        if (!Yii::app()->user->isAuthenticated()) {
            $dbCriteria->mergeWith(
                [
                    'condition' => 't.is_protected = :is_protected',
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
                'pageSize'=>$pageSize,
            ],
        ]);

        return $dataProvider;
    }
}
