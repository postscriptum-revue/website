<section
	style="--issue-color: <?= $last_issue->color() ?>">
	<h2 class="site-aside__title">Dernier numéro</h2>
	<div class="latest-issues__issue-cover"
		style="background-image: url('<?= $last_issue->cover()->toFile()->url() ?>')">
		<hgroup class="last-issue__cover-card">
			<p class="last-issue__cover-card-date">
				<?= formatDate($last_issue->date()) ?>
			</p>
			<h2 class="last-issue__cover-card-title">
				<?= $last_issue->title()->smartypants() ?>
			</h2>
			<?php if ($last_issue->subtitle() != "") : ?>
				<p class="last-issue__cover-card-subtitle">
					<?= $last_issue->subtitle() ?>
				</p>
			<?php endif ?>
		</hgroup>
	</div>
	<div class="last-issue__toc">
		<?php snippet("site/toc", [
			"toc_pages" => $last_issue->children(),
		]) ?>
	</div>
</section>