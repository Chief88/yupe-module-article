<?php

Yii::import('application.modules.article.models.*');

class GetArticleWidget extends yupe\widgets\YWidget
{
    /** @var $categories mixed Список категорий, из которых выбирать статьи. NULL - все */
    public $categorySlugs = null;
    public $limit = 3;
    public $view = 'listArticle';

    public function run()
    {
        $criteria = new CDbCriteria();
        $criteria->with[] = 'category';
        $criteria->condition = 't.status = :status';
        $criteria->params = [
            ':status' => Article::STATUS_PUBLISHED,
        ];

        if ($this->categorySlugs) {
            if (is_array($this->categorySlugs)) {
                $criteria->addCondition('category.slug = :categorySlugs');
                $criteria->params[':categorySlugs'] = $this->categorySlugs;
            } else {
                $criteria->addCondition('category.slug = :categorySlugs');
                $criteria->params[':categorySlugs'] = $this->categorySlugs;
            }
        }

        if ($this->controller->isMultilang()) {
            $criteria->mergeWith(
                [
                    'condition' => 't.lang = :lang',
                    'params'    => [':lang' => Yii::app()->language],
                ]
            );
        }

        if($this->limit){
            $criteria->limit = (int)$this->limit;
        }
        $criteria->order = 't.sort';

        $models = Article::model()->findAll($criteria);
        $this->render($this->view, ['models' => $models]);
    }
}
