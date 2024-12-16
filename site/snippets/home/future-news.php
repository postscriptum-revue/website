<?php if (count($future_news) > 0): ?>
	<section class="home-section recent-posts">
		<h2 class="home-section__title">Appels et événements</h2>
		<ul class="toc">
			<?php foreach ($future_news as $news_item): ?>
				<li class="toc__item_horizontal">
					<?php
					if ($cover = $news_item->cover()->toFile()): ?>
						<picture>
							<img src="<?= $cover->url() ?>" alt="cover">
						</picture>
					<?php endif; ?>

					<div>
						<a href="<?= $news_item->url() ?>">
							<ul>
								<li>
									<small><?= formatDate($news_item->date(), "MMMM yyyy") ?></small>
								</li>
								<li>
									<?= $news_item->blueprint()->title() ?>
								</li>
								<li class="news-list__item-title">
									<?= $news_item->title() ?>
								</li>
							</ul>
						</a>
					</div>

				</li>
			<?php endforeach ?>
		</ul>
	</section>
<?php endif; ?>