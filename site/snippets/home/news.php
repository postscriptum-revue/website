<ul class="news-list">
    <?php foreach ($all_news as $news_item) : ?>
        <li class="news-list__item">
            <a href="<?= $news_item->url() ?>">
                <ul>
                    <li>
                        <?php
                        $date = substr_replace(
                            $news_item->num(),
                            "/",
                            4,
                            0
                        );
                        $date = substr_replace(
                            $date,
                            "/",
                            -2,
                            0
                        );
                        echo $date
                        ?>
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
