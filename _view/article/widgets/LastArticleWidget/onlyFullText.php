<?php Yii::import('application.modules.article.ArticleModule'); ?>

<?php if (isset($models) && $models != array()): ?>
    <?php foreach ($models as $model): ?>

        <div><?php echo $model->full_text; ?></div>

    <?php endforeach; ?>
<?php endif; ?>