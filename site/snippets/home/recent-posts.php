<?php
$recentPosts = [];

// Get the "comptes-rendus" within the last 3 months
$comptesrendus = page('comptes-rendus')->children()->listed()->sortBy('date', 'desc');
foreach ($comptesrendus as $comptesrendu) {
    if ($comptesrendu && $comptesrendu->date()->toDate() >= strtotime('-3 months')) {
        array_push($recentPosts, $comptesrendu);
    }
}

// Get the "entretiens" within the last 3 months
$entretiens = page('entretiens')->children()->listed()->sortBy('date', 'desc');
foreach ($entretiens as $entretien) {
    if ($entretien && $entretien->date()->toDate() >= strtotime('-3 months')) {
        array_push($recentPosts, $entretien);
    }
}

// Get the "creations" within the last 3 months
$creations = page('creations')->children()->listed()->sortBy('date', 'desc');
foreach ($creations as $creation) {
    if ($creation && $creation->date()->toDate() >= strtotime('-3 months')) {
        array_push($recentPosts, $creation);
    }
}

// Display the recent posts if there are any
if (count($recentPosts) > 0): ?>
    <section class="home-section last-issue">
        <h2 class="home-section__title">Publications récentes</h2>
        <?php foreach ($recentPosts as $p): ?>
            <ul class="toc">
                <li class="toc__item" style="--issue-color: <?= $p->parent()->color() ?>">
                    <div style="display: flex; flex-direction: row;">

                        <!-- <?php if ($cover = $p->cover()->toFile()): ?>
                            <div style="
                            background-image: url('<?= $cover->url() ?>');
                            background-size: cover;
                            background-position: center;
                            width: 150px;
                            height: 100px;
                            ">
                            </div>
                        <?php endif; ?> -->

                        <div>
                            <small>
                                <?= $template = $p->template() == 'review' ?
                                    'Compte-rendu' :
                                    'Entretien'
                                    ?>
                            </small>

                            <a href="<?= $p->url() ?>">
                                <ul class="toc__item-authors-list">
                                    <?php
                                    foreach ($p->authors()->toStructure() as $author): ?>
                                        <li class="toc__item-author"><?= $author->name() ?></li>
                                    <?php endforeach ?>
                                </ul>
                                <p class="toc__item-title"><?= $p->title_and_subtitle() ?></p>
                                <p><?= formatDate($p->date()) ?></p>
                            </a>
                        </div>
                    </div>
                </li>
            </ul>
        <?php endforeach ?>
    </section>

    <!-- <article class="home-section last-issue">
        <h2 class="home-section__title">Recentes publications</h2>
        <ul>
            <?php foreach ($recentPosts as $post): ?>
                <li>
                    <a href="<?= $post->url() ?>">
                        <ul>
                            <?php
                            foreach ($post->authors()->toStructure() as $author): ?>
                                <li><?= $author->name() ?></li>
                            <?php endforeach ?>
                        </ul>
                        <p><?= $post->title_and_subtitle() ?></p>
                        <p><?= formatDate($post->date()) ?></p>
                    </a>


                </li>
            <?php endforeach; ?>
        </ul>
    </article> -->
<?php endif; ?>