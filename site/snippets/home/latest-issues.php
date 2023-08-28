<section class="home-section latest-issues">
	<h2 class="home-section__title">Parutions récentes</h2>
	<?php foreach ($latest_issues as $issue) : ?>
		<a href="<?= $issue->url() ?>">
			<article class="latest-issues__issue" style="--issue-color: <?= $issue->color() ?>">
				<div class="latest-issues__issue-cover" style="background-image: url('<?= $issue->cover()->toFile()->url() ?>')">
					<div class="latest-issues__issue-card">
						<p class="latest-issues__issue-card-number">PS<?= $issue->num() ?></p>
					</div>
				</div>
				<hgroup class="latest-issues__issue-hgroup">
					<p class="latest-issues__issue-date"><?= $issue->date() ?></p>
					<h3 class="latest-issues__issue-title"><?= $issue->title() ?></h3>
				</hgroup>
			</article>
		</a>
	<?php endforeach ?>
</section>