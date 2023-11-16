<?php snippet("site/layout", slots: true) ?>

<?php slot("aside") ?>

<h2 class="site-aside__title">Actualités</h2>
<ul>
	<?php foreach ($all_news as $n) : ?>
		<li class="home-aside__news-item">
			<!-- TODO: Placeholder code. Use real date. -->
			<date>
				<?= substr_replace(
					substr_replace($n->num(), "/", -2, 0), "/", 4, 0
				) ?>
			</date>
			<p><?= $n->parent()->title() ?></p>
			<p>
				PS<?= $n->issue_number() ?>.
				<?= $n->issue_title()->smartypants() ?>
				<?php if ($n->issue_subtitle() != "") : ?>
					<?= $n->issue_subtitle() ?>
				<?php endif ?>
			</p>
		</li>
	<?php endforeach ?>
</ul>
<?php endslot() ?>

<?php slot("main") ?>
<?php snippet("home/last-issue") ?>
<?php snippet("home/latest-issues") ?>
<?php endslot() ?>
