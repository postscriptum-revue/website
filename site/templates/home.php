<?php snippet("site/layout", slots: true) ?>

<?php slot("aside") ?>

<h2 class="site-aside__title">Actualités</h2>
<ul>
	<?php foreach ($all_news as $n) : ?>
		<li>
			<article class="home-aside__news-item">
				<date><?= $n->num() ?></date>
				<p><?= $n->parent()->title() ?></p>
				<p>
					PS<?= $n->issue_number() ?>.
					<?= $n->issue_title()->smartypants() ?> :
					<?= $n->issue_subtitle() ?>
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
