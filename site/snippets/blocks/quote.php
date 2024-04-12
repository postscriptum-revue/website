<blockquote class="text-section__blockquote">
	<?php
	if (
		$page->template() == "interview"
		&& $block->citation()->isNotEmpty()
	) : ?>
		<header class="text-section__blockquote-header">
			<?= $block->citation() ?>
		</header>
	<?php endif ?>
	<?= $block->text()->smartypants() ?>
	<?php
	if (
		$page->template() != "interview"
		&& $block->citation()->isNotEmpty()
	) : ?>
		<footer class="text-section__blockquote-footer">
			<?= $block->citation() ?>
		</footer>
	<?php endif ?>
</blockquote>
