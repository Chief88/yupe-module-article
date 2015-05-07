<script type='text/javascript'>
    $(document).ready(function () {
        $('#article-form').liTranslit({
            elName: '#Article_title',
            elAlias: '#Article_alias'
        });
    })
</script>

<?php
$form = $this->beginWidget(
    'bootstrap.widgets.TbActiveForm',
    [
        'id'                     => 'article-form',
        'enableAjaxValidation'   => false,
        'enableClientValidation' => true,
        'type'                   => 'vertical',
        'htmlOptions'            => ['class' => 'well', 'enctype' => 'multipart/form-data'],
    ]
); ?>
<div class="alert alert-info">
    <?php echo Yii::t('ArticleModule.article', 'Fields with'); ?>
    <span class="required">*</span>
    <?php echo Yii::t('ArticleModule.article', 'are required'); ?>
</div>

<?php echo $form->errorSummary($model); ?>

<div class="row">

    <div class="col-sm-4">
        <?php echo $form->datePickerGroup(
            $model,
            'date',
            [
                'widgetOptions' => [
                    'options' => [
                        'format'    => 'dd-mm-yyyy',
                        'weekStart' => 1,
                        'autoclose' => true,
                    ],
                ],
                'prepend'       => '<i class="fa fa-calendar"></i>',
            ]
        );
        ?>
    </div>

    <div class="col-sm-2">
        <?php echo $form->dropDownListGroup(
            $model,
            'status',
            [
                'widgetOptions' => [
                    'data' => $model->getStatusList(),
                ],
            ]
        ); ?>
    </div>

    <div class="col-sm-2">
        <?php if (count($languages) > 1): { ?>
            <?php echo $form->dropDownListGroup(
                $model,
                'lang',
                [
                    'widgetOptions' => [
                        'data'        => $languages,
                        'htmlOptions' => [
                            'empty' => Yii::t('ArticleModule.article', '-no matter-'),
                        ],
                    ],
                ]
            ); ?>
            <?php if (!$model->isNewRecord): { ?>
                <?php foreach ($languages as $k => $v): { ?>
                    <?php if ($k !== $model->lang): { ?>
                        <?php if (empty($langModels[$k])): { ?>
                            <a href="<?php echo $this->createUrl(
                                '/article/articleBackend/create',
                                ['id' => $model->id, 'lang' => $k]
                            ); ?>"><i class="iconflags iconflags-<?php echo $k; ?>" title="<?php echo Yii::t(
                                    'ArticleModule.article',
                                    'Add translation for {lang} language',
                                    ['{lang}' => $v]
                                ) ?>"></i></a>
                        <?php } else: { ?>
                            <a href="<?php echo $this->createUrl(
                                '/article/articleBackend/update',
                                ['id' => $langModels[$k]]
                            ); ?>"><i class="iconflags iconflags-<?php echo $k; ?>" title="<?php echo Yii::t(
                                    'ArticleModule.article',
                                    'Edit translation in to {lang} language',
                                    ['{lang}' => $v]
                                ) ?>"></i></a>
                        <?php } endif; ?>
                    <?php } endif; ?>
                <?php } endforeach; ?>
            <?php } endif; ?>
        <?php } else: { ?>
            <?php echo $form->hiddenField($model, 'lang'); ?>
        <?php } endif; ?>
    </div>

</div>

<div class="row">
    <div class="col-sm-7">
        <?php echo $form->dropDownListGroup(
            $model,
            'category_id',
            [
                'widgetOptions' => [
                    'data'        => Category::model()->getFormattedList(
                            (int)Yii::app()->getModule('article')->mainCategory
                        ),
                    'htmlOptions' => [
                        'empty'  => Yii::t('ArticleModule.article', '--choose--'),
                        'encode' => false
                    ],
                ],
            ]
        ); ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-7">
        <?php echo $form->textFieldGroup($model, 'title'); ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-7">
        <?php echo $form->textFieldGroup($model, 'alias'); ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-7">
        <?php echo $form->textFieldGroup($model, 'name_author'); ?>
    </div>
</div>

<div class='row'>
    <div class="col-sm-7">
        <?php
        echo CHtml::image(
            !$model->isNewRecord && $model->image ? $model->getImageUrl() : '#',
            $model->title,
            [
                'class' => 'preview-image',
                'style' => !$model->isNewRecord && $model->image ? '' : 'display:none'
            ]
        ); ?>
        <?php echo $form->fileFieldGroup(
            $model,
            'image',
            [
                'widgetOptions' => [
                    'htmlOptions' => [
                        'onchange' => 'readURL(this);',
                        'style'    => 'background-color: inherit;'
                    ]
                ]
            ]
        ); ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-7">
        <?php echo $form->textFieldGroup($model, 'video_url'); ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-12 <?php echo $model->hasErrors('full_text') ? 'has-error' : ''; ?>">
        <?php echo $form->labelEx($model, 'full_text'); ?>
        <?php $this->widget(
            $this->module->getVisualEditor(),
            [
                'model'     => $model,
                'attribute' => 'full_text',
            ]
        ); ?>
        <span class="help-block">
            <?php echo Yii::t(
                'ArticleModule.article',
                'Full text article which will be shown on article article page'
            ); ?>
        </span>
        <?php echo $form->error($model, 'full_text'); ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <?php echo $form->labelEx($model, 'short_text'); ?>
        <?php $this->widget(
            $this->module->getVisualEditor(),
            [
                'model'     => $model,
                'attribute' => 'short_text',
            ]
        ); ?>
        <span class="help-block">
            <?php echo Yii::t(
                'ArticleModule.article',
                'article anounce text. Usually this is the main idea of the article.'
            ); ?>
        </span>
        <?php echo $form->error($model, 'short_text'); ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-7">
        <?php echo $form->textFieldGroup($model, 'link'); ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-7">
        <?php echo $form->checkBoxGroup($model, 'is_protected', $model->getProtectedStatusList()); ?>
    </div>
</div>

<div class="row hidden">
    <div class="col-sm-7">
        <?php echo $form->textFieldGroup($model, 'sort'); ?>
    </div>
</div>

<?php $collapse = $this->beginWidget('bootstrap.widgets.TbCollapse'); ?>
<div class="panel-group" id="extended-options">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">
                <a data-toggle="collapse" data-parent="#extended-options" href="#collapseOne">
                    <?php echo Yii::t('ArticleModule.article', 'Data for SEO'); ?>
                </a>
            </div>
        </div>
        <div id="collapseOne" class="panel-collapse collapse">
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-7">
                        <?php echo $form->textAreaGroup($model, 'page_title'); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-7">
                        <?php echo $form->textAreaGroup($model, 'seo_keywords'); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-7">
                        <?php echo $form->textAreaGroup($model, 'seo_description'); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-7">
                        <?php echo $form->checkBoxGroup($model, 'no_index', $model->getProtectedStatusList()); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>

<br/>

<?php $this->widget(
    'bootstrap.widgets.TbButton',
    [
        'buttonType' => 'submit',
        'context'    => 'primary',
        'label'      => $model->isNewRecord ? Yii::t('ArticleModule.article', 'Create article and continue') : Yii::t(
                'ArticleModule.article',
                'Save article article and continue'
            ),
    ]
); ?>

<?php $this->widget(
    'bootstrap.widgets.TbButton',
    [
        'buttonType'  => 'submit',
        'htmlOptions' => ['name' => 'submit-type', 'value' => 'index'],
        'label'       => $model->isNewRecord ? Yii::t('ArticleModule.article', 'Create article and close') : Yii::t(
                'ArticleModule.article',
                'Save article article and close'
            ),
    ]
); ?>

<?php $this->endWidget(); ?>
