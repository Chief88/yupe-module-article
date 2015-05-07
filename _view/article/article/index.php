<?php
if (!empty($category)) {
    $this->description = !empty($category->seo_description) ? $category->seo_description : $this->description;
    $this->keywords = !empty($category->seo_keywords) ? $category->seo_keywords : $this->keywords;
    $this->pageTitle = !empty($category->page_title) ? $category->page_title : $this->pageTitle;
} ?>

<div class="content">

    <div class="patern-container">
        <h1>Статьи</h1>
    </div>

    <?php $this->widget(
        'bootstrap.widgets.TbListView',
        [
            'dataProvider'  => $dataProvider,
            'itemView'      => '_view',
            'template'      => "{items}\n{pager}",
            'itemsCssClass' => 'news-list',
            'itemsTagName'  => 'ul',
            'pager' => [
                'cssFile' => false,
                'prevPageLabel' => '',
                'firstPageLabel' => '',
                'nextPageLabel' => '',
                'lastPageLabel' => '',
                'previousPageCssClass' => 'page-list-nav page-prev',
                'firstPageCssClass' => 'page-list-nav page-first',
                'nextPageCssClass' => 'page-list-nav page-next',
                'lastPageCssClass' => 'page-list-nav page-last',
                'selectedPageCssClass' => 'selected no-click',
                'header' => '',
                'htmlOptions' => [
                    'class' => 'page-list',
                ],

            ],
        ]
    ); ?>

</div>