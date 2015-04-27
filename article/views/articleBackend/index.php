<?php

/**
 * @var $model article
 * @var $this articleBackendController
 */

$this->breadcrumbs = [
    Yii::t('ArticleModule.article', 'article') => ['/article/articleBackend/index'],
    Yii::t('ArticleModule.article', 'Management'),
];

$this->pageTitle = Yii::t('ArticleModule.article', 'article - management');

$this->menu = [
    [
        'icon'  => 'fa fa-fw fa-list-alt',
        'label' => Yii::t('ArticleModule.article', 'article management'),
        'url'   => ['/article/articleBackend/index']
    ],
    [
        'icon'  => 'fa fa-fw fa-plus-square',
        'label' => Yii::t('ArticleModule.article', 'Create article'),
        'url'   => ['/article/articleBackend/create']
    ],
]; ?>

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
    $this->renderPartial('_search', ['model' => $model]);
    ?>
</div>

<?php $this->widget(
    'yupe\widgets\CustomGridView',
    [
        'id'                => 'article-grid',
        'sortableRows'      => true,
        'sortableAjaxSave'  => true,
        'sortableAttribute' => 'sort',
        'sortableAction'    => '/article/articleBackend/sortable',
        'dataProvider'      => $model->search(),
        'filter'            => $model,
        'columns'           => [
            [
                'class'    => 'bootstrap.widgets.TbEditableColumn',
                'name'     => 'title',
                'editable' => [
                    'url'    => $this->createUrl('/article/articleBackend/inline'),
                    'mode'   => 'inline',
                    'params' => [
                        Yii::app()->request->csrfTokenName => Yii::app()->request->csrfToken
                    ]
                ],
                'filter'   => CHtml::activeTextField($model, 'title', ['class' => 'form-control']),
            ],
            [
                'class'    => 'bootstrap.widgets.TbEditableColumn',
                'name'     => 'alias',
                'editable' => [
                    'url'    => $this->createUrl('/article/articleBackend/inline'),
                    'mode'   => 'inline',
                    'params' => [
                        Yii::app()->request->csrfTokenName => Yii::app()->request->csrfToken
                    ]
                ],
                'filter'   => CHtml::activeTextField($model, 'alias', ['class' => 'form-control']),
            ],
            'date',
            [
                'name'   => 'category_id',
                'value'  => '$data->getCategoryName()',
                'filter' => CHtml::activeDropDownList(
                    $model,
                    'category_id',
                    Category::model()->getFormattedList(Yii::app()->getModule('article')->mainCategory),
                    ['class' => 'form-control', 'encode' => false, 'empty' => '']
                )
            ],
            [
                'class'   => 'yupe\widgets\EditableStatusColumn',
                'name'    => 'status',
                'url'     => $this->createUrl('/article/articleBackend/inline'),
                'source'  => $model->getStatusList(),
                'options' => [
                    article::STATUS_PUBLISHED  => ['class' => 'label-success'],
                    article::STATUS_MODERATION => ['class' => 'label-warning'],
                    article::STATUS_DRAFT      => ['class' => 'label-default'],
                ],
            ],
            [
                'name'   => 'lang',
                'value'  => '$data->getFlag()',
                'filter' => $this->yupe->getLanguagesList(),
                'type'   => 'html'
            ],
            [
                'class'                => '\yupe\widgets\ToggleColumn',
                'name'                 => 'no_index',
                'checkedButtonLabel'   => Yii::t('ArticleModule.article', 'Turn off'),
                'uncheckedButtonLabel' => Yii::t('ArticleModule.article', 'Turn on'),
                'filter'               => $model->getNoIndexList(),
            ],
            [
                'class' => 'yupe\widgets\CustomButtonColumn'
            ],
        ],
    ]
); ?>
