<section
	class="home-section last-issue"
	style="--issue-color: <?= $last_issue->color() ?>"
>
	<h2 class="home-section__title">Dernier numéro</h2>
	<a
		href="<?= $last_issue->url() ?>"
		class="latest-issues__issue-book last-issue__book"
	>
		<p class="latest-issues__issue-card-number">
			<span style="
				font-feature-settings:
					'ss<?= $last_issue->logo_style_p() ?>';
			">P</span><span style="
				font-feature-settings:
					'ss<?= $last_issue->logo_style_s() ?>';
			">S</span><?= $last_issue->num() ?>
		</p>
		<hgroup class="latest-issues__issue-hgroup">
			<h3 class="latest-issues__issue-title">
				<?= $last_issue->title()->smartypants() ?>
			</h3>
			<?php if ($last_issue->subtitle()->isNotEmpty()) : ?>
				<p class="latest-issues__issue-subtitle">
					<?= $last_issue->subtitle()->smartypants() ?>
				</p>
			<?php endif ?>
			<time class="latest-issues__issue-date">
				<?= formatDate($last_issue->issued_date(), "MMMM yyyy") ?>
			</time>
		</hgroup>
		<div
			class="latest-issues__issue-cover"
			style="--cover: url('<?= $last_issue->cover()->toFile()->url() ?>')"
		></div>
	</a>
	<div class="last-issue__toc">
		<?php snippet("site/toc", [
			"toc_pages" => $last_issue->children(),
			"hiddenDate" => true,
			"max" => 6
		]) ?>
	</div>
</section>
