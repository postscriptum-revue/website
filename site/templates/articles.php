<?php snippet("site/layout", slots: true) ?>

<?php slot("aside") ?>
<section class="site-aside__section">
	<h2 class="site-aside__title">Mots-clés</h2>
	<ul class="site-aside__list">
		<?php foreach ($keywords as $kw) : ?>
			<li class="site-aside__list-item">
				<a href="<?= $page->url(['params' => ['keyword' => $kw]]) ?>"><?= $kw ?></a>
			</li>
		<?php endforeach ?>
	</ul>
</section>
<?php endslot() ?>

<?php slot("main") ?>
<?php foreach ($articles as $article) : ?>
	<a href="<?= $article->url() ?>" class="article">
		<article>
			<ul class="article__authors">
				<?php foreach ($article->authors()->toStructure() as $author) : ?>
					<li><?= $author->name() ?></li>
				<?php endforeach ?>
			</ul>
			<hgroup class="article__hgroup">
				<h2 class="article__title"><?= $article->title() ?></h2>
				<p class="article__subtitle"><?= $article->subtitle() ?></p>
			</hgroup>
		</article>
	</a>
<?php endforeach ?>
<?php endslot() ?>