<section
	class="home-section last-issue"
	style="--issue-color: <?= $last_issue->color() ?>"
>
	<h2 class="home-section__title">Dernier numéro</h2>
	<a
		href="<?= $last_issue->url() ?>"
		class="last-issue__cover"
		style="background-image: url('<?= $last_issue->cover()->toFile()->url() ?>')"
	>
		<hgroup class="last-issue__cover-card">
			<p class="last-issue__cover-card-date">
				<?= $last_issue->date() ?>
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
	</a>
	<div class="last-issue__toc">
		<?php snippet("site/toc", [
			"toc_pages" => $last_issue->children(),
			"max" => 6
		]) ?>
	</div>
</section>
