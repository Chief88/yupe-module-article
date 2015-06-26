<?php Yii::import('application.modules.article.ArticleModule'); ?>

<?php if (isset($models) && $models != []): ?>

    <div class="right-side-news">
        <div class="block-title"><a href="/article">Статьи</a></div>

        <?php foreach ($models as $model): ?>

            <div class="news-item">
                <div class="n-date"><?php echo $model->date; ?></div>
                <div class="n-title">
                    <a href="/article/<?php echo $model->slug; ?>"><?php echo $model->title; ?></a>
                </div>
            </div>

        <?php endforeach; ?>
    </div>

<?php endif; ?>