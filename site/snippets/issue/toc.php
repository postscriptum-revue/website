<?php
// TODO: Put this in a controller
$templates = [
	[
		"name" => "article",
		"label" => "Articles"
	],
	[
		"name" => "interview",
		"label" => "Entrevues"
	]
];

?>

<section class="issue-toc">
	<?php foreach ($templates as $template) : ?>
		<?php $pages_with_template = $page->children()->filterBy("template", $template["name"]) ?>
		<?php if (count($pages_with_template) > 0) : ?>
			<h3 class="site-aside__title"><?= $template["label"] ?></h3>
			<?php foreach ($pages_with_template as $page_with_template) : ?>
				<a href="<?= $page_with_template->url() ?>" class="issue-toc__item">
					<article>
						<ul class="issue-toc__item-authors">
							<?php foreach ($page_with_template->authors()->toStructure() as $author) : ?>
								<li><?= $author->name() ?></li>
							<?php endforeach ?>
						</ul>
						<p class="issue-toc__item-title"><?= $page_with_template->title() ?> : <?= $page_with_template->subtitle() ?></p>
						<!-- <p class="issue-toc__item-subtitle"><?= $page_with_template->subtitle() ?></p> -->
					</article>
				</a>
			<?php endforeach ?>
		<?php endif ?>
	<?php endforeach ?>
</section>