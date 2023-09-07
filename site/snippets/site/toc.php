<ul class="toc">
	<?php foreach ($toc_pages as $toc_page) : ?>
		<li class="toc__item">
			<a href="<?= $toc_page->url() ?>">
				<article>
					<ul class="toc__item-authors">
						<?php
						foreach ($toc_page->authors()->toStructure()
							as $author) : ?>
							<li><?= $author->name() ?></li>
						<?php endforeach ?>
					</ul>
					<p class="toc__item-title">
						<?= $toc_page->title() ?>
					</p>
					<?php if ($toc_page->subtitle() != "") : ?>
						<p class="toc__item-subtitle">
							<?= $toc_page->subtitle() ?>
						</p>
					<?php endif ?>
				</article>
			</a>
		</li>
	<?php endforeach ?>
</ul>