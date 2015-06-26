<?php
$this->breadcrumbs = [
    Yii::t('ArticleModule.article', 'article') => ['/article/articleBackend/index'],
    Yii::t('ArticleModule.article', 'Create'),
];

$this->pageTitle = Yii::t('ArticleModule.article', 'article - create');

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
];
?>
<div class="page-header">
    <h1>
        <?php echo Yii::t('ArticleModule.article', 'article'); ?>
        <small><?php echo Yii::t('ArticleModule.article', 'create'); ?></small>
    </h1>
</div>

<?php echo $this->renderPartial('_form', ['model' => $model, 'languages' => $languages]); ?>
