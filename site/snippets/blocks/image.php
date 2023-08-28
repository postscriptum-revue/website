<figure class="image-block">
	<!-- TODO: Add alt -->
	<img class="image-block__image" src="<?= $block->image()->toFile()->url() ?>" alt="">
	<?php if ($block->caption()) : ?>
		<figcaption class="image-block__caption"><?= $block->caption()->widont() ?></figcaption>
	<?php endif ?>
</figure>