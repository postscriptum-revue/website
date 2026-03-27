<?php $cover = $page->cover()->toFile() ?>
<header class="issue-header" data-type="issue">
	<?php if ($cover): ?>
		<figure class="issue-header__cover">
			<img
				class="issue-header__cover-image"
				src="<?= $cover->url() ?>"
				alt="<?= $cover->alt_text()->esc() ?>">
			<figcaption><?= $cover->credit()->esc() ?></figcaption>
		</figure>
	<?php endif ?>
	<div class="issue-header__card-wrapper">
		<hgroup class="issue-header__card">
			<p><?= formatDate($page->issued_date(), "MMMM yyyy") ?></p>
			<h1 class="issue-header__card-title">
				<?= $page->title()->smartypants() ?>
			</h1>
			<p class="issue-header__card-subtitle">
				<?= $page->subtitle() ?>
			</p>
		</hgroup>
	</div>
</header>
