<section class="home-section latest-issues">
	<h2 class="home-section__title">Parutions récentes</h2>
	<?php foreach (page("parutions")->children() as $issue) : ?>
		<a href="<?= $issue->url() ?>">
			<article class="latest-issues__issue" style="--issue-color: <?= $issue->color() ?>">
				<div class="latest-issues__issue-cover" style="background-image: url('<?= $issue->cover()->toFile()->url() ?>')">
					<div class="latest-issues__issue-card">
						<p class="latest-issues__issue-card-number">PS<?= $issue->num() ?></p>
					</div>
				</div>
				<hgroup>
					<p><?= $issue->date() ?></p>
					<h3><?= $issue->title() ?></h3>
				</hgroup>
			</article>
		</a>
	<?php endforeach ?>
</section>