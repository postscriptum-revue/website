<?php snippet("site/layout", slots: true) ?>

<?php slot("aside") ?>

<?php endslot() ?>

<?php slot("main") ?>
<?php foreach ($articles as $article) : ?>
	<article class="article">
		<ul class="article__authors">
			<?php foreach ($article->authors()->toStructure() as $author) : ?>
				<li><?= $author->name() ?></li>
			<?php endforeach ?>
		</ul>
		<hgroup>
			<h2 class="article__title"><?= $article->title() ?></h2>
			<p class="article__subtitle"><?= $article->subtitle() ?></p>
		</hgroup>
	</article>
<?php endforeach ?>
<?php endslot() ?>