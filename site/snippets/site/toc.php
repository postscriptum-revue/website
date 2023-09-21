<ul class="toc">
	<?php foreach ($toc_pages as $p) : ?>
		<li class="toc__item">
			<a href="<?= $p->url() ?>">
				<ul class="toc__item-authors">
					<?php
					foreach ($p->authors()->toStructure()
						as $author) : ?>
						<li><?= $author->name() ?></li>
					<?php endforeach ?>
				</ul>
				<p class="toc__item-title">
					<?php if ($p->formatted_title()->isNotEmpty()) : ?>
						<?= $p->formatted_title()->smartypants() ?>
					<?php else : ?>
						<?= $p->title()->smartypants() ?>
					<?php endif ?>
				</p>
				<?php if ($p->subtitle() != "") : ?>
					<p class="toc__item-subtitle">
						<?= $p->subtitle() ?>
					</p>
				<?php endif ?>
			</a>
		</li>
	<?php endforeach ?>
</ul>