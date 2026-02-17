<figure class="image-block">
	<img
		class="image-block__image"
		src="<?= $block->image()->toFile()->url() ?>"
		alt="<?= $block->alt()->esc() ?>"
		loading="lazy"
	>
	<?php if ($block->caption()) : ?>
		<figcaption class="image-block__caption">
			<?= $block->caption()->widont() ?>
		</figcaption>
	<?php endif ?>
</figure>