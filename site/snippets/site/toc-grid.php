<?php
// Allow templates to specify how many pages to display in the
// table of content. If `$max` is unset, display all pages.
$max = $max ?? null;
?>

<?php foreach ($toc_pages->slice(0, $max) as $p): ?>
	<?php $color = $p->template() == 'article' ?
		$p->parent()->color() : $p->color() ?>
	<article class="toc__item" style="--issue-color: <?= $color ?>">

		<!-- COVER IMG -->
		<?php
		if ($cover = $p->cover()->toFile()): ?>
			<picture class="toc-grid__item-cover">
				<img src="<?= $cover->url() ?>" alt="cover">
			</picture>
		<?php else: ?>
			<div class="toc-grid__item-cover_blank"></div>
		<?php endif; ?>


		<a href="<?= $p->url() ?>">
			<ul class="toc__item-authors-list">
				<?php
				foreach ($p->authors()->toStructure() as $author): ?>
					<li class="toc__item-author"><?= $author->name() ?></li>
				<?php endforeach ?>
			</ul>
			<p class="toc__item-title"><?= $p->title_and_subtitle() ?></p>
			<?php if (!isset($hiddenDate)): ?>
				<p><?= formatDate($p->date(), "MMMM yyyy") ?></p>
			<?php endif ?>
		</a>
	</article>
<?php endforeach ?>
<?php
// When there are more pages than amount displayed.
if ($max && $max < count($toc_pages)):
?>
	<article class="toc__item">
		...
	</article>
<?php endif ?>