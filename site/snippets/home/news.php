<ul class="news-list">
    <?php foreach ($all_news as $news_item) : ?>
        <li class="news-list__item">
            <a href="<?= $news_item->url() ?>">
                <ul>
                    <li>
                        <?= formatDate($news_item->date(), "dd.MM.yyyy") ?>
                    </li>
                    <li>
                        <?= $news_item->blueprint()->title() ?>
                    </li>
                    <li class="news-list__item-title">
                        <?= $news_item->title() ?>
                    </li>
                </ul>
            </a>
        </li>
    <?php endforeach ?>
</ul>
