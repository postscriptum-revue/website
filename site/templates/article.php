<?php snippet("site/layout", slots: true) ?>

<?php slot("aside") ?>
<!-- Boutons : télécharger en format PDF, Retour en haut de la page -->
<section class="site-aside__section article-summary">
	<h2 class="site-aside__title">Résumé (fr)</h2>
	<?= $page->summary_fr() ?>
</section>
<section class="site-aside__section article-summary">
	<h2 class="site-aside__title">Résumé (en)</h2>
	<?= $page->summary_en() ?>
</section>
<section class="site-aside__section article-bibliography">
	<h2 class="site-aside__title">Bibliographie</h2>
	<?= $page->bibliography() ?>
</section>
<?php endslot() ?>

<?php slot("main") ?>
<section class="text-section">
	<hgroup class="text-section__hgroup">
		<ul class="text-section__hgroup-authors">
			<?php foreach ($page->authors()->toStructure() as $author) : ?>
				<li><?= $author->name() ?></li>
			<?php endforeach ?>
		</ul>
		<h1 class="text-section__hgroup-title"><?= $page->title() ?></h1>
		<p class="text-section__hgroup-subtitle"><?= $page->subtitle() ?></p>
	</hgroup>
	<?= $page->text()->toBlocks() ?>
</section>
<?php endslot() ?>