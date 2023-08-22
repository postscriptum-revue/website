<header class="issue-header">
	<figure class="issue-header__cover">
		<img class="issue-header__cover-image" src="<?= $page->cover()->toFile()->url() ?>" alt="">
		<figcaption><!-- TODO: Add figcaption field --></figcaption>
	</figure>
	<div class="issue-header__card-wrapper">
		<hgroup class="issue-header__card">
			<p><?= ucfirst($page->date()->toDate("MMMM Y")) ?></p>
			<h1 class="issue-header__card-title"><?= $page->title() ?></h1>
			<p class="issue-header__card-subtitle"><?= $page->subtitle() ?></p>
		</hgroup>
	</div>
</header>