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
		<?php
		$pages_with_template = $page
			->children()->filterBy("template", $template["name"])
		?>
		<?php if (count($pages_with_template) > 0) : ?>
			<?php
			snippet("site/toc", ["toc_pages" => $pages_with_template])
			?>
		<?php endif ?>
	<?php endforeach ?>
</section>
