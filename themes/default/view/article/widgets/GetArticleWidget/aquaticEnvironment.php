<h4 class="lined-header">
    <span class="lined-header__content">Водная среда</span>
</h4>

<div class="sepa-20"></div>

<?php $relationCategory = $models[0]->category; ?>
<?php if(!empty($relationCategory->image)):{ ?>
    <div class="row">
        <img src="<?= $relationCategory->getImageUrl(980, 0); ?>"
             alt="<?= $relationCategory->name; ?>"
             class="col-lg-12 col-md-12 col-sm-12 col-xs-12" />
    </div>
    <div class="sepa-30"></div>
<?php }endif;?>

<?php if(!empty($relationCategory->description)):{ ?>

    <div class="row">
        <div class="ol-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?= $relationCategory->description; ?>
        </div>
    </div>
    <div class="sepa-30"></div>

<?php }endif; ?>

<ul class="programs">

    <?php foreach($models as $model):{ ?>
        <?php if(empty($model->timetableLesson)):{ ?>

            <li class="programs__item">
<!--                <div class="programs__img row">-->
<!---->
<!--                    --><?php //if( !empty($model->image) ):{ ?>
<!--                        <img src="--><?//= $model->getImageUrl(320); ?><!--"-->
<!--                             alt="--><?//= $model->title; ?><!--"-->
<!--                             class="col-lg-12 col-md-12 col-sm-12 col-xs-12" />-->
<!--                    --><?php //}endif; ?>
<!---->
<!--                </div>-->
                <div class="programs__title"><?= $model->title; ?></div>
                <div class="programs__content">
                    <?= $model->full_text; ?>
                </div>
        <?php }endif; ?>
    <?php }endforeach; ?>

</ul>

<div class="load-content">
    <a href="/timetable?type=vodnaya-sreda" class="btn btn-primary">Смотреть расписание</a>
</div>