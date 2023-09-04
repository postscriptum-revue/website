<a href="<?= $issue->url() ?>">
	<article class="latest-issues__issue" style="--issue-color: <?= $issue->color() ?>">
		<div class="latest-issues__issue-cover" style="background-image: url('<?php if ($issue->cover() != "") echo $issue->cover()->toFile()->url() ?>')">
			<div class="latest-issues__issue-card">
				<p class="latest-issues__issue-card-number">PS<?= $issue->num() ?></p>
			</div>
		</div>
		<hgroup class="latest-issues__issue-hgroup">
			<time class="latest-issues__issue-date"><?= $issue->date() ?></time>
			<h3 class="latest-issues__issue-title"><?= $issue->title() ?></h3>
		</hgroup>
	</article>
</a>