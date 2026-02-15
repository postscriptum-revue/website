<header class="site-header">
	<?php snippet("site/logo") ?>
	<nav class="site-header__nav">
		<ul class="site-header__nav-list">
			<?php foreach ($site->children()->listed()->filterBy('intendedTemplate', 'not in', ['articles', 'news']) as $item): ?>
				<li>
					<a
						class="site-header__nav-list-item"
						href="<?= $item->url() ?>"
						<?= $item->isOpen() ? 'aria-current="page"' : '' ?>>
						<?= $item->title()->esc() ?>
					</a>
				</li>
			<?php endforeach ?>
		</ul>
	</nav>
</header>
