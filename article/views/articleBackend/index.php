<?php

/**
 * @var $model article
 * @var $this articleBackendController
 */

$this->breadcrumbs = array(
    Yii::t('ArticleModule.article', 'article') => array('/article/articleBackend/index'),
    Yii::t('ArticleModule.article', 'Management'),
);

$this->pageTitle = Yii::t('ArticleModule.article', 'article - management');

$this->menu = array(
    array(
        'icon'  => 'fa fa-fw fa-list-alt',
        'label' => Yii::t('ArticleModule.article', 'article management'),
        'url'   => array('/article/articleBackend/index')
    ),
    array(
        'icon'  => 'fa fa-fw fa-plus-square',
        'label' => Yii::t('ArticleModule.article', 'Create article'),
        'url'   => array('/article/articleBackend/create')
    ),
);
?>
<div class="page-header">
    <h1>
        <?php echo Yii::t('ArticleModule.article', 'article'); ?>
        <small><?php echo Yii::t('ArticleModule.article', 'management'); ?></small>
    </h1>
</div>

<p>
    <a class="btn btn-default btn-sm dropdown-toggle" data-toggle="collapse" data-target="#search-toggle">
        <i class="fa fa-search">&nbsp;</i>
        <?php echo Yii::t('ArticleModule.article', 'Find article'); ?>
        <span class="caret">&nbsp;</span>
    </a>
</p>

<div id="search-toggle" class="collapse out search-form">
    <?php
    Yii::app()->clientScript->registerScript(
        'search',
        "
    $('.search-form form').submit(function () {
        $.fn.yiiGridView.update('article-grid', {
            data: $(this).serialize()
        });

        return false;
    });
"
    );
    $this->renderPartial('_search', array('model' => $model));
    ?>
</div>

<?php $this->widget(
    'yupe\widgets\CustomGridView',
    array(
        'id'                => 'article-grid',
        'sortableRows'      => true,
        'sortableAjaxSave'  => true,
        'sortableAttribute' => 'sort',
        'sortableAction'    => '/article/articleBackend/sortable',
        'dataProvider'      => $model->search(),
        'filter'            => $model,
        'columns'           => array(
            array(
                'class'    => 'bootstrap.widgets.TbEditableColumn',
                'name'     => 'title',
                'editable' => array(
                    'url'    => $this->createUrl('/article/articleBackend/inline'),
                    'mode'   => 'inline',
                    'params' => array(
                        Yii::app()->request->csrfTokenName => Yii::app()->request->csrfToken
                    )
                ),
                'filter'   => CHtml::activeTextField($model, 'title', array('class' => 'form-control')),
            ),
            array(
                'class'    => 'bootstrap.widgets.TbEditableColumn',
                'name'     => 'alias',
                'editable' => array(
                    'url'    => $this->createUrl('/article/articleBackend/inline'),
                    'mode'   => 'inline',
                    'params' => array(
                        Yii::app()->request->csrfTokenName => Yii::app()->request->csrfToken
                    )
                ),
                'filter'   => CHtml::activeTextField($model, 'alias', array('class' => 'form-control')),
            ),
            'date',
            array(
                'name'   => 'category_id',
                'value'  => '$data->getCategoryName()',
                'filter' => CHtml::activeDropDownList(
                    $model,
                    'category_id',
                    Category::model()->getFormattedList(Yii::app()->getModule('article')->mainCategory),
                    array('class' => 'form-control', 'encode' => false, 'empty' => '')
                )
            ),
            array(
                'class'   => 'yupe\widgets\EditableStatusColumn',
                'name'    => 'status',
                'url'     => $this->createUrl('/article/articleBackend/inline'),
                'source'  => $model->getStatusList(),
                'options' => [
                    article::STATUS_PUBLISHED  => ['class' => 'label-success'],
                    article::STATUS_MODERATION => ['class' => 'label-warning'],
                    article::STATUS_DRAFT      => ['class' => 'label-default'],
                ],
            ),
            array(
                'name'   => 'lang',
                'value'  => '$data->getFlag()',
                'filter' => $this->yupe->getLanguagesList(),
                'type'   => 'html'
            ),
            array(
                'class' => 'yupe\widgets\CustomButtonColumn'
            ),
        ),
    )
); ?>
