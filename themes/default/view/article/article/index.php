<?php if (!empty($categoryModel)) {

    $this->pageTitle = !empty($categoryModel->page_title) ? $categoryModel->page_title : $this->pageTitle;
    $this->metaDescription = !empty($categoryModel->seo_description) ? $categoryModel->seo_description : $this->metaDescription;
    $this->metaKeywords = !empty($categoryModel->seo_keywords) ? $categoryModel->seo_keywords : $this->metaKeywords;
    $this->metaNoIndex = $categoryModel->no_index == 1 ? true : false;

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