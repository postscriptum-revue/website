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
					<hgroup class="toc__item-title">
						<span><?= $toc_page->title() ?></span>
						<span><?= ($subtitle = $toc_page->subtitle())->isNotEmpty() ? ' : ' . $subtitle : '' ?></span>
					</hgroup>
				</article>
			</a>
		</li>
	<?php endforeach ?>
</ul>