<?php snippet("site/layout", slots: true) ?>

<?php slot("aside") ?>

<!-- Boutons : télécharger en format PDF, Retour en haut de la page -->
<?php if (( $keywords = $page->keywords() ) ->isNotEmpty()) : ?>
	<section class="site-aside__section">
	<h2 class="site-aside__title">Mots-clés</h2>
	<ul class="site-aside__list">
	<?php foreach ($keywords->split() as $kw) : ?>
		<li class="site-aside__list-item"><?= $kw ?></li>
	<?php endforeach ?>
	</ul>
	</section>
<?php endif ?>
<?php if ( ($resume_fr = $page->abstract_fr())->isNotEmpty() ) : ?>
	<section class="site-aside__section article-summary">
	<h2 class="site-aside__title">Résumé (fr)</h2>
	<?= $resume_fr ?>
	</section>
<?php endif ?>
<?php if ( ($abstract_en = $page->abstract_en())->isNotEmpty() ) : ?>
	<section class="site-aside__section article-summary">
	<h2 class="site-aside__title">Résumé (en)</h2>
	<?= $abstract_en ?>
	</section>
<?php endif ?>
<?php if ( ($bibliography = $page->bibliography())->isNotEmpty() ) : ?>
	<section class="site-aside__section article-bibliography">
	<h2 class="site-aside__title">Bibliographie</h2>
	<?= $bibliography ?>
	</section>
<?php endif ?>
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