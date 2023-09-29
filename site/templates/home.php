<?php snippet("site/layout", slots: true) ?>

<?php slot("aside") ?>

<h2 class="site-aside__title">Actualités</h2>
<ul>
	<?php foreach ($all_news as $news_item) : ?>
		<li>
			<article class="home-aside__news-item">
				<!-- TODO: Alt text -->
				<!-- <figure class="home-aside__news-item-img">
					<img src="<?= $news_item->issue_cover()->toFile()->url() ?>" alt="">
				</figure> -->
				<p><?= $news_item->parent()->title() ?></p>
				<p>
					PS<?= $news_item->issue_number() ?>.
					<?= $news_item->issue_title() ?> :
					<?= $news_item->issue_subtitle() ?>
				</p>
			</article>
		</li>
	<?php endforeach ?>
</ul>
<?php endslot() ?>

<?php slot("main") ?>
<?php snippet("home/last-issue") ?>
<?php snippet("home/latest-issues") ?>
<?php endslot() ?>