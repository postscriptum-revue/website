<a href="<?= $issue->url() ?>">
	<article class="latest-issues__issue" style="--issue-color: <?= $issue->color() ?>">
		<div class="latest-issues__issue-book">
			<p class="latest-issues__issue-card-number">
				<span style="
					font-feature-settings:
						'ss<?= $issue->logo_style_p() ?>';
				">P</span><span style="
					font-feature-settings:
						'ss<?= $issue->logo_style_s() ?>';
				">S</span><?= $issue->num() ?>
			</p>
			<hgroup class="latest-issues__issue-hgroup">
				<h3 class="latest-issues__issue-title">
					<?= $issue->title()->smartypants() ?>
				</h3>
				<?php if ($issue->subtitle()->isNotEmpty()) : ?>
					<p class="latest-issues__issue-subtitle">
						<?= $issue->subtitle()->smartypants() ?>
					</p>
				<?php endif ?>
				<time class="latest-issues__issue-date">
					<?= formatDate($issue->issued_date(), "MMMM yyyy") ?>
				</time>
			</hgroup>
			<div class="latest-issues__issue-cover" style="--cover: url(
					'<?= $issue->cover()->toFile()->url() ?>'
				)"></div>
		</div>
	</article>
</a>
