<header class="issue-header" data-type="issue">
	<figure class="issue-header__cover">
		<img
			class="issue-header__cover-image"
			src="<?= $page->cover()->toFile()->url() ?>"
			alt="">
		<figcaption><!-- TODO: Add figcaption field --></figcaption>
	</figure>
	<div class="issue-header__card-wrapper">
		<hgroup class="issue-header__card">
			<p><?= formatDate($page->date(), "MMMM yyyy") ?></p>
			<h1 class="issue-header__card-title">
				<?= $page->title()->smartypants() ?>
			</h1>
			<p class="issue-header__card-subtitle">
				<?= $page->subtitle() ?>
			</p>
		</hgroup>
	</div>
</header>