<blockquote class="text-section__blockquote">
	<?= $block->text() ?>
	<?php if ($block->citation()->isNotEmpty()) : ?>
		<footer class="text-section__blockquote-footer">
			<?= $block->citation() ?>
		</footer>
	<?php endif ?>
</blockquote>