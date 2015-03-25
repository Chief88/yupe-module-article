<?php
$this->breadcrumbs = array(
    Yii::t('ArticleModule.article', 'Article') => array('/article/articleBackend/index'),
    $model->title,
);

$this->pageTitle = Yii::t('ArticleModule.article', 'Article - show');

$this->menu = array(
    array(
        'icon'  => 'fa fa-fw fa-list-alt',
        'label' => Yii::t('ArticleModule.article', 'Article management'),
        'url'   => array('/article/articleBackend/index')
    ),
    array(
        'icon'  => 'fa fa-fw fa-plus-square',
        'label' => Yii::t('ArticleModule.article', 'Create article'),
        'url'   => array('/article/articleBackend/create')
    ),
    array('label' => Yii::t('ArticleModule.article', 'Article Article') . ' «' . mb_substr($model->title, 0, 32) . '»'),
    array(
        'icon'  => 'fa fa-fw fa-pencil',
        'label' => Yii::t('ArticleModule.article', 'Edit Article article'),
        'url'   => array(
            '/article/articleBackend/update',
            'id' => $model->id
        )
    ),
    array(
        'icon'  => 'fa fa-fw fa-eye',
        'label' => Yii::t('ArticleModule.article', 'View Article article'),
        'url'   => array(
            '/article/articleBackend/view',
            'id' => $model->id
        )
    ),
    array(
        'icon'        => 'fa fa-fw fa-trash-o',
        'label'       => Yii::t('ArticleModule.article', 'Remove Article'),
        'url'         => '#',
        'linkOptions' => array(
            'submit'  => array('/article/articleBackend/delete', 'id' => $model->id),
            'params'  => array(Yii::app()->getRequest()->csrfTokenName => Yii::app()->getRequest()->csrfToken),
            'confirm' => Yii::t('ArticleModule.article', 'Do you really want to remove the article?'),
            'csrf'    => true,
        )
    ),
);
?>

<div class="page-header">
    <h1>
        <?php echo Yii::t('ArticleModule.article', 'Show Article article'); ?><br/>
        <small>&laquo;<?php echo $model->title; ?>&raquo;</small>
    </h1>
</div>

<ul class="nav nav-tabs">
    <li class="active"><a href="#anounce" data-toggle="tab"><?php echo Yii::t(
                'ArticleModule.article',
                'Short Article article example'
            ); ?></a></li>
    <li><a href="#full" data-toggle="tab"><?php echo Yii::t('ArticleModule.article', 'Full Article article example'); ?></a></li>
</ul>
<div class="tab-content">
    <div id="anounce" class="tab-pane fade active in">
        <div style="margin-bottom: 20px;">
            <h6>
                <span class="label label-info"><?php echo $model->date; ?></span>
                <?php echo CHtml::link($model->title, array('/Article/Article/show', 'alias' => $model->alias)); ?>
            </h6>

            <p>
                <?php echo $model->short_text; ?>
            </p>
            <i class="fa fa-fw fa-globe"></i><?php echo CHtml::link(
                $model->getPermaLink(),
                $model->getPermaLink()
            ); ?>
        </div>
    </div>
    <div id="full" class="tab-pane fade">
        <div style="margin-bottom: 20px;">
            <h3><?php echo CHtml::link(
                    CHtml::encode($model->title),
                    array('/Article/Article/show', 'alias' => $model->alias)
                ); ?></h3>
            <?php if ($model->image): { ?>
                <?php echo CHtml::image($model->getImageUrl(), $model->title); ?>
            <?php } endif; ?>
            <p><?php echo $model->full_text; ?></p>
            <span class="label label-info"><?php echo $model->date; ?></span>
            <i class="fa fa-fw fa-user"></i><?php echo CHtml::link(
                $model->user->fullName,
                array('/user/people/' . $model->user->nick_name)
            ); ?>
            <i class="fa fa-fw fa-globe"></i><?php echo CHtml::link(
                $model->getPermaLink(),
                $model->getPermaLink()
            ); ?>
        </div>
    </div>
</div>
