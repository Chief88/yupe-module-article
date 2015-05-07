<?php
$this->pageTitle = !empty($article->page_title) ? $article->page_title : $article->title;
$this->metaDescription = !empty($article->seo_description) ? $article->seo_description : $this->metaDescription;
$this->metaKeywords = !empty($article->seo_keywords) ? $article->seo_keywords : $this->metaKeywords;
$this->metaNoIndex = $article->no_index == 1 ? true : false;
?>

<div class="content">

    <div class="patern-container">
        <h1><?php echo $article->title; ?></h1>
    </div>

    <div class="news-page-date"><?php echo $article->date; ?></div>

    <?php echo $article->full_text; ?>

    <div class="news-page-all"><a href="/article">« Другие Статьи</a></div>

    <div id="vk_like"></div>

</div>
