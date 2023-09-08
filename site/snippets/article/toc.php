<?php $parent = $page->parent() ?>

<section class="issue-toc">
    <h3 class="site-aside__title">
        <a href="<?= $parent->url() ?>">
            <?= 'Aussi dans ' . $page->parent()->title() ?>
        </a>
    </h3>
    <?php snippet("site/toc", ["toc_pages" => $page->siblings()]) ?>
</section>