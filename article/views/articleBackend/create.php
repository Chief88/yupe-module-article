<?php
$this->breadcrumbs = array(
    Yii::t('ArticleModule.article', 'article') => array('/article/articleBackend/index'),
    Yii::t('ArticleModule.article', 'Create'),
);

$this->pageTitle = Yii::t('ArticleModule.article', 'article - create');

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
        <small><?php echo Yii::t('ArticleModule.article', 'create'); ?></small>
    </h1>
</div>

<?php echo $this->renderPartial('_form', array('model' => $model, 'languages' => $languages)); ?>
