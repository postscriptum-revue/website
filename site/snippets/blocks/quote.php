<blockquote class="text-section__blockquote">
	<?php
	$isInterview = isset($page) && $page->template() == "interview";
	if ($isInterview && $block->citation()->isNotEmpty()) : ?>
		<header class="text-section__blockquote-header">
			<?= $block->citation() ?>
		</header>
	<?php endif ?>
	<?= $block->text()->smartypants() ?>
	<?php if (!$isInterview && $block->citation()->isNotEmpty()) : ?>
		<footer class="text-section__blockquote-footer">
			<?= $block->citation() ?>
		</footer>
	<?php endif ?>
</blockquote>
