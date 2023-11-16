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
				<p class="toc__item-title">
					<?php if ($p->formatted_title()->isNotEmpty()) : ?>
						<?= $p->formatted_title()->smartypants() ?>
					<?php else : ?>
						<?= $p->title()->smartypants() ?>
					<?php endif ?>
				</p>
				<?php if ($p->subtitle() != "") : ?>
					<p class="toc__item-subtitle">
						<?= $p->subtitle() ?>
					</p>
				<?php endif ?>
			</a>
		</li>
	<?php endforeach ?>
	<!-- When there are more pages than amount displayed. -->
	<?php if ($max != -1 && $max < count($toc_pages)) : ?>
		<li class="toc__item">
			...
		</li>
	<?php endif ?>
</ul>
