<section class="home-section last-issue" style="--issue-color: <?= $last_issue->color() ?>">
	<h2 class="home-section__title">Dernière parution</h2>
	<a href="<?= $last_issue->url() ?>" class="last-issue__cover" style="background-image: url('<? if ($last_issue->cover()) echo $last_issue->cover()->toFile()->url() ?>')">
		<hgroup class="last-issue__cover-card">
			<p class="last-issue__cover-card-date"><?= $last_issue->date() ?></p>
			<h2 class="last-issue__cover-card-title"><?= $last_issue->title() ?></h2>
			<p class="last-issue__cover-card-subtitle"><?= $last_issue->subtitle() ?></p>
		</hgroup>
	</a>
	<div class="last-issue__toc">
		<?php snippet("site/toc", ["toc_pages" => $last_issue->children()]) ?>
	</div>
</section>