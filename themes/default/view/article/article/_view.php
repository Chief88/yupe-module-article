<li>
    <div class="news-list-date"><?php echo $data->date; ?></div>
    <div class="news-list-title">
        <a href="/article/<?php echo $data->slug; ?>"><?php echo $data->title; ?></a>
    </div>
    <div class="news-list-anonce">
        <?php echo $data->short_text; ?>
    </div>
</li>