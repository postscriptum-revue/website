<?php

return function ($page) {
	$meta_authors = [];

	$meta_authors =	preg_replace('/<\/li>\s*<li>/', ' ', $page->credit_editors());
	$meta_authors = strip_tags($meta_authors);

	$meta_description = "";

	$first_p = $page->intro_text()->toBlocks()->first();

	if ($first_p->citation()->isNotEmpty()) {
		$first_p = $page->intro_text()->toBlocks()->nth(1);
	}

	$first_p_clean = $first_p->text()->kirbytext()->excerpt();
	$meta_description = substr($first_p_clean, 0, 150) . "[...]";


	return [
		"cover" => $page->cover()->toFile(),
		"meta_authors" => $meta_authors,
		"meta_description" => $meta_description
	];
};