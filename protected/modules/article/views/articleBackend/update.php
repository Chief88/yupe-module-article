<?php
$this->breadcrumbs = [
    Yii::t('ArticleModule.article', 'article') => ['/article/articleBackend/index'],
    $model->title                     => ['/article/articleBackend/view', 'id' => $model->id],
    Yii::t('ArticleModule.article', 'Edit'),
];

$this->pageTitle = Yii::t('ArticleModule.article', 'article - edit');

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
    ['label' => Yii::t('ArticleModule.article', 'article Article') . ' «' . mb_substr($model->title, 0, 32) . '»'],
    [
        'icon'  => 'fa fa-fw fa-pencil',
        'label' => Yii::t('ArticleModule.article', 'Edit article article'),
        'url'   => [
            '/article/articleBackend/update/',
            'id' => $model->id
        ]
    ],
    [
        'icon'  => 'fa fa-fw fa-eye',
        'label' => Yii::t('ArticleModule.article', 'View article article'),
        'url'   => [
            '/article/articleBackend/view',
            'id' => $model->id
        ]
    ],
    [
        'icon'        => 'fa fa-fw fa-trash-o',
        'label'       => Yii::t('ArticleModule.article', 'Remove article'),
        'url'         => '#',
        'linkOptions' => [
            'submit'  => ['/article/articleBackend/delete', 'id' => $model->id],
            'params'  => [Yii::app()->getRequest()->csrfTokenName => Yii::app()->getRequest()->csrfToken],
            'confirm' => Yii::t('ArticleModule.article', 'Do you really want to remove the article?'),
            'csrf'    => true,
        ]
    ],
];
?>
<div class="page-header">
    <h1>
        <?php echo Yii::t('ArticleModule.article', 'Edit article article'); ?><br/>
        <small>&laquo;<?php echo $model->title; ?>&raquo;</small>
    </h1>
</div>

<?php echo $this->renderPartial(
    '_form',
    ['model' => $model, 'languages' => $languages, 'langModels' => $langModels]
); ?>
