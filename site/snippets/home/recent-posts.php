<?php
// Display the recent posts if there are any
if (count($recentPosts) > 0): ?>
	<section class="home-section recent-posts">
		<h2 class="home-section__title">Publications récentes</h2>
		<ul class="toc">
			<?php foreach ($recentPosts as $p): ?>
				<li class="toc__item_horizontal" style="--issue-color: <?= $p->parent()->color() ?>">
						<?php
						if ($cover = $p->cover()->toFile()): ?>
							<picture>
								<img src="<?= $cover->url() ?>" alt="cover">
							</picture>
						<?php endif; ?>

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
				</li>

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