<hgroup class="text-section-hgroup">
	<?php if ($cover = $page->cover()->toFile()): ?>
		<header class="issue-header">
			<figure class="issue-header__cover">
				<img class="issue-header__cover-image" src="<?= $cover->url() ?>" alt="">
				<figcaption><!-- TODO: Add figcaption field --></figcaption>
			</figure>
			<div class="issue-header__card-wrapper">
				<hgroup class="issue-header__card">
					<p><?= formatDate($page->date()) ?></p>
					<h1 class="issue-header__card-title">
						<?= $page->title()->smartypants() ?>
					</h1>
					<p class="issue-header__card-subtitle">
						<?= $page->subtitle() ?>
					</p>
				</hgroup>
			</div>
		</header>
	<?php else: ?>

		<ul class="text-section-hgroup__authors">
			<?php foreach ($page->authors()->toStructure() as $author): ?>
				<li>
					<ul>
						<li class="text-section-hgroup__author-name">
							<?= $author->name() ?>
						</li>
						<?php if ($author->affiliation()->isNotEmpty()): ?>
							<li class="text-section-hgroup__author-affiliation">
								(<?= $author->affiliation() ?>)
							</li>
						<?php endif ?>
					</ul>
				</li>
			<?php endforeach ?>
		</ul>
		<h1 class="text-section-hgroup__title">
			<?php if ($page->formatted_title()->isNotEmpty()): ?>
				<?= $page->formatted_title()->smartypants() ?>
			<?php else: ?>
				<?= $page->title()->smartypants() ?>
			<?php endif ?>
		</h1>
		<p class="text-section-hgroup__subtitle">
			<?= $page->subtitle() ?>
		</p>

	<?php endif ?>
</hgroup>