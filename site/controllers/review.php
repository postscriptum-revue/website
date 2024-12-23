<?php

return function ($page) {
	$issue_color = $page->parent()->color();

	$meta_authors = [];

	foreach ($page->authors()->toStructure() as $author):
		$authors[] = $author->name();
	endforeach;

	$meta_authors = implode(",", $authors);

	//in order, use the following to fill the meta-tag description:
	//1 - french abstract
	//2 - the 150 first caracters of the first paragraphe that is texte (no image, no link, no quote)
	$meta_description = "";
	if ($page->abstract_fr() != '') {
		$meta_description = substr(strip_tags($page->abstract_fr()), 0, 150) . "[...]";
	} else {
		$first_p = $page->text()->toBlocks()->filterBy('type', 'text')->first();
		$first_p_clean = $first_p->text()->kirbytext()->excerpt();
		$meta_description = substr($first_p_clean, 0, 150) . "[...]";
	}

	return [
		"issue_color" => $issue_color,
		"meta_authors" => $meta_authors,
		"meta_description" => $meta_description
	];
};
