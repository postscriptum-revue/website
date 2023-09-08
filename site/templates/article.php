<?php snippet("site/layout", slots: true) ?>

<?php slot("aside") ?>

<?php snippet("article/toc") ?>

<!-- Boutons : télécharger en format PDF, Retour en haut de la page -->
<?php if (($keywords = $page->keywords())->isNotEmpty()) {
	echo '<section class="site-aside__section">
	<h2 class="site-aside__title">Mots-clés</h2>
	<ul class="site-aside__list">';
	foreach ($keywords->split() as $kw)
		echo '<li class="site-aside__list-item">'
			. $kw
			. '</li>';
	echo '</ul>
	</section>';
} ?>
<?php if (($resume_fr = $page->abstract_fr())->isNotEmpty())
	echo '<section class="site-aside__section article-summary">
	<h2 class="site-aside__title">Résumé (fr)</h2>'
		. $resume_fr
		. '</section>';
?>
<?php if (($abstract_en = $page->abstract_en())->isNotEmpty())
	echo '<section class="site-aside__section article-summary">
	<h2 class="site-aside__title">Résumé (en)</h2>'
		. $abstract_en
		. '</section>';
?>
<?php if (($bibliography = $page->bibliography())->isNotEmpty())
	echo '<section class="site-aside__section article-bibliography">
	<h2 class="site-aside__title">Bibliographie</h2>'
		. $bibliography
		. '</section>';
?>
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