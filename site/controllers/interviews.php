<?php

use Kirby\Cms\Pages;

return function ($site) {
	$authors = [];
	$keywords = [];
	$interviews = $site->index()->filterBy('template', 'interview');

	foreach ($interviews as $interview) {
		foreach ($interview->keywords()->split() as $kw) {
			array_push($keywords, $kw);
		}
	}
	sort($keywords);

	$keyword = param('keyword');
	if (empty($keyword) === false) {
		$interviews = $interviews->filterBy('keywords', $keyword, ',');
	}

	return [
		"interviews" => $interviews,
		"authors" => $authors,
		"keywords" => $keywords
	];
};
