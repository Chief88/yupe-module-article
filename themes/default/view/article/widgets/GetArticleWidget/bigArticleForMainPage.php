<?php
/**
 * @var $models array
 * @var $model Article
 */
?>

<?php if (count($models) > 0): { ?>
	<?php  foreach($models as $model):{ ?>
		<div class="main-article">
			<div class="row">
				<div class="col-4-9">
					<div class="main-article__img">
						<img src="<?= $model->getImageUrl(299, 412); ?>" alt="<?= $model->title; ?>"/>
					</div>
				</div>
				<div class="col-5-9">
					<div class="main-article__title">
						<?= $model->title; ?>
					</div>
					<div class="main-article__more">
						<a href="<?= $model->getPermaLink(); ?>" class="btn btn-default">
							<?= Yii::t('app', 'Learn more'); ?>
						</a>
					</div>
					<div class="main-article__desc">
						<?= $model->short_text; ?>
					</div>
				</div>
			</div>
		</div>
	<?php }endforeach; ?>
<?php }endif; ?>