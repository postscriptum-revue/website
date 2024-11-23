<?php

$recent_news = [];
foreach ($all_news as $n) {
    if ($n && $n->date()->toDate() >= strtotime('-3 months')) {
        array_push($recent_news, $n);
    }
}

if (count($recent_news) > 0) : ?>

<ul class="news-list">
    <?php foreach ($recent_news as $news) : ?>
        <li class="news-list__item">
            <a href="<?= $news->url() ?>">
                <ul>
                    <li>
                        <?= formatDate($news->date(), "dd.MM.yyyy") ?>
                    </li>
                    <li>
                        <?= $all->blueprint()->title() ?>
                    </li>
                    <li class="news-list__item-title">
                        <?= $news->title() ?>
                    </li>
                </ul>
            </a>
        </li>
    <?php endforeach ?>
</ul>

<?php endif ?>