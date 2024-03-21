<hgroup class="text-section-hgroup">
	<ul class="text-section-hgroup__authors">
		<?php foreach ($page->authors()->toStructure() as $author) : ?>
			<li>
				<ul>
					<li class="text-section-hgroup__author-name">
						<?= $author->name() ?>
					</li>
					<?php if ($author->affiliation()->isNotEmpty()) : ?>
						<li class="text-section-hgroup__author-affiliation">
							(<?= $author->affiliation() ?>)
						</li>
					<?php endif ?>
				</ul>
			</li>
		<?php endforeach ?>
	</ul>
	<h1 class="text-section-hgroup__title">
		<?php if ($page->formatted_title()->isNotEmpty()) : ?>
			<?= $page->formatted_title()->smartypants() ?>
		<?php else : ?>
			<?= $page->title()->smartypants() ?>
		<?php endif ?>
	</h1>
	<p class="text-section-hgroup__subtitle">
		<?= $page->subtitle() ?>
	</p>
</hgroup>
