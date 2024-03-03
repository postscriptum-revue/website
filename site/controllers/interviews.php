<?php

use Kirby\Cms\Pages;

return function () {
	$authors = [];
	$keywords = [];
	$interviews = new Pages();
	$numeros = page("numeros")->children();

	foreach ($numeros as $n) {
		$n_interviews = $n->children()->filterBy("template", "interview");
		$interviews->add($n_interviews);
	}

	foreach ($interviews as $interview) {
		foreach ($interview->keywords()->split() as $kw) {
			array_push($keywords, $kw);
		}
	}

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
