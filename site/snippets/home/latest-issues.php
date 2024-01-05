<section class="home-section latest-issues">
	<h2 class="home-section__title">Numéros récents</h2>
	<?php foreach ($latest_issues as $issue) : ?>
		<?php snippet("site/issue-small", ["issue" => $issue]) ?>
	<?php endforeach ?>
</section>
