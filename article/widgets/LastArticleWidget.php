<?php

Yii::import('application.modules.article.models.*');

class LastArticleWidget extends yupe\widgets\YWidget
{
    /** @var $categories mixed Список категорий, из которых выбирать статьи. NULL - все */
    public $categories = null;

    public $view = 'lastArticleWidget';

    public function run()
    {
        $criteria = new CDbCriteria();
        $criteria->limit = (int)$this->limit;
        $criteria->order = 'sort';

        if ($this->categories) {
            if (is_array($this->categories)) {
                $criteria->addInCondition('category_id', $this->categories);
            } else {
                $criteria->compare('category_id', $this->categories);
            }
        }

        $article = ($this->controller->isMultilang())
            ? Article::model()->published()->language(Yii::app()->language)->cache($this->cacheTime)->findAll($criteria)
            : Article::model()->published()->cache($this->cacheTime)->findAll($criteria);

        $this->render($this->view, ['models' => $article]);
    }
}
