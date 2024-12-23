<?php

return function ($page) {
	$issue_color = $page->parent()->color();

	$meta_authors = [];

	foreach ($page->authors()->toStructure() as $author):
		$authors[] = $author->name();
	endforeach;

	$meta_authors = implode(",", $authors);

	$meta_description = "";

	//in order, use the following to fill the meta-tag description:
	//1 - french abstract
	//2 - citation if the first paragraphe does not exist or if it is a link
	//3 - the 150 first caracters of the main text
	if ($page->abstract_fr() != '') {
		$meta_description = substr(strip_tags($page->abstract_fr()), 0, 150) . "[...]";
	} else {
		$first_p = $page->text()->toBlocks()->first();

		if (!$first_p || str_contains($first_p, "http")) {
			$meta_description = $page->title_and_subtitle() . " Post-Scriptum " . $page->parent()->num() . "(" . $page->fmt_date() . ")";
		} else {
			$first_p_clean = $first_p->text()->kirbytext()->excerpt();
			$meta_description = substr($first_p_clean, 0, 150) . "[...]";
		}
	}


	return [
		"issue_color" => $issue_color,
		"meta_authors" => $meta_authors,
		"meta_description" => $meta_description
	];
};
