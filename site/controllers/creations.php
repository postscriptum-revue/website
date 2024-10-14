<?php

use Kirby\Cms\Pages;

return function ($site) {
	$authors = [];
	$keywords = [];
	$creations = $site->index()
		->filterBy('template', 'creation')->listed()
		->sortBy('date', 'desc');


	foreach ($creations as $creation) {
		foreach ($creation->keywords()->split() as $kw) {
			array_push($keywords, $kw);
		}
	}
	sort($keywords);

	$keyword = param('keyword');
	if (empty($keyword) === false) {
		$creations = $creations->filterBy('keywords', $keyword, ',');
	}

	return [
		"creations" => $creations,
		"authors" => $authors,
		"keywords" => $keywords
	];
};
