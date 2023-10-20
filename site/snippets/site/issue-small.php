<a href="<?= $issue->url() ?>">
	<article 
		class="latest-issues__issue" 
		style="--issue-color: <?= $issue->color() ?>"
	>
		<div 
			class="latest-issues__issue-cover" 
			style="background-image: url(
				'<?= $issue->cover()->toFile()->url() ?>'
			)"
		>
			<div class="latest-issues__issue-card">
				<p class="latest-issues__issue-card-number">
					<span style="
						font-feature-settings: 
							'ss<?= $issue->logo_style_p() ?>';
					">P</span><span style="
						font-feature-settings: 
							'ss<?= $issue->logo_style_s() ?>';
					">S</span><?= $issue->num() ?>
				</p>
			</div>
		</div>
		<hgroup class="latest-issues__issue-hgroup">
			<time class="latest-issues__issue-date">
				<?= $issue->date() ?>
			</time>
			<h3 class="latest-issues__issue-title">
				<?= $issue->title() ?>
			</h3>
		</hgroup>
	</article>
</a>