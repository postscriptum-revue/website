<?php
// Display the recent posts if there are any
if (count($recent_posts) > 0): ?>
	<section class="home-section recent-posts">
		<h2 class="home-section__title">Publications récentes</h2>
		<ul class="toc">
			<?php foreach ($recent_posts as $p): ?>
				<li class="toc__item_horizontal" style="--issue-color: <?= $p->parent()->color() ?>">
					<?php
					if ($cover = $p->cover()->toFile()): ?>
						<picture>
							<img src="<?= $cover->url() ?>" alt="cover">
						</picture>
					<?php endif; ?>

					<div>
						<p>
							<?= $p->blueprint()->title() ?>
						</p>
						<small><?= formatDate($p->date(), "MMMM yyyy") ?></small>

						<a href="<?= $p->url() ?>">
							<ul class="toc__item-authors-list">
								<?php
								foreach ($p->authors()->toStructure() as $author): ?>
									<li class="toc__item-author"><?= $author->name() ?></li>
								<?php endforeach ?>
							</ul>
							<p class="toc__item-title"><?= $p->title_and_subtitle() ?></p>
						</a>
					</div>
				</li>

			<?php endforeach ?>
	</section>
<?php endif; ?>