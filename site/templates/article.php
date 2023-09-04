<?php snippet("site/layout", slots: true) ?>

<?php slot("aside") ?>

<!-- Boutons : télécharger en format PDF, Retour en haut de la page -->

<?php if ($page->keywords() != "") : ?>
	<section class="site-aside__section">
		<h2 class="site-aside__title">Mots-clés</h2>
		<ul class="site-aside__list">
			<?php foreach ($page->keywords()->split() as $kw) : ?>
				<li class="site-aside__list-item"><?= $kw ?></li>
			<?php endforeach ?>
		</ul>
	</section>
<?php endif ?>

<section class="site-aside__section article-summary">
	<h2 class="site-aside__title">Résumé (fr)</h2>
	<?= $page->abstract_fr() ?>
</section>

<section class="site-aside__section article-summary">
	<h2 class="site-aside__title">Résumé (en)</h2>
	<?= $page->abstract_en() ?>
</section>

<section class="site-aside__section article-bibliography">
	<h2 class="site-aside__title">Bibliographie</h2>
	<?= $page->bibliography() ?>
</section>

<?php endslot() ?>

<?php slot("main") ?>

<hgroup class="text-section-hgroup">
	<ul class="text-section-hgroup__authors">
		<?php foreach ($page->authors()->toStructure() as $author) : ?>
			<li><?= $author->name() ?></li>
		<?php endforeach ?>
	</ul>
	<h1 class="text-section-hgroup__title"><?= $page->title() ?></h1>
	<p class="text-section-hgroup__subtitle"><?= $page->subtitle() ?></p>
</hgroup>

<section class="text-section">
	<?= $page->text()->toBlocks() ?>
</section>

<?php endslot() ?>