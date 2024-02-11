<?php
// Allow templates to specify how many pages to display in the
// table of content. If `$max` is unset, display all pages.
$max = $max ?? null;
?>

<ul class="toc">
	<?php foreach ($toc_pages->slice(0, $max) as $p) : ?>
		<li
			class="toc__item"
			style="--issue-color: <?= $p->parent()->color() ?>"
		>
			<a href="<?= $p->url() ?>">
				<ul class="toc__item-authors-list">
					<?php
					foreach ($p->authors()->toStructure()
						as $author) : ?>
						<li class="toc__item-author"><?= $author->name() ?></li>
					<?php endforeach ?>
				</ul>
				<p class="toc__item-title"><?= $p->title_and_subtitle() ?></p>
			</a>
		</li>
	<?php endforeach ?>
	<?php
	// When there are more pages than amount displayed.
	if ($max && $max < count($toc_pages)) :
	?>
		<li class="toc__item">
			...
		</li>
	<?php endif ?>
</ul>
