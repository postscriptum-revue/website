<?php snippet("site/layout", slots: true) ?>

<?php slot("aside") ?>
<section class="site-aside__section">
	<h2 class="site-aside__title">Mots-clés</h2>
	<ul class="site-aside__list">
		<?php foreach ($keywords as $kw) : ?>
			<li class="site-aside__list-item">
				<a 
					href="<?= $page->url(
						['params' => ['keyword' => $kw]]
					) ?>"
				>
					<?= $kw ?>
				</a>
			</li>
		<?php endforeach ?>
	</ul>
</section>
<?php endslot() ?>

<?php slot("main") ?>
<?php snippet("site/toc", [
		"toc_pages" => $articles->sortBy("title", "desc")
	]) ?>
<?php endslot() ?>