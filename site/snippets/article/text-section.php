<?php snippet("article/heading") ?>

<section class="text-section">
	<?= $page->text()->toBlocks()->collectFootnotes() ?>
</section>

<?= $page->footnotes() ?>
