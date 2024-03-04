<?php

use Kirby\Cms\Pages;

return function () {
	$authors = [];
	$keywords = [];
	$reviews = new Pages();
	$numeros = page("numeros")->children();

	foreach ($numeros as $n) {
		$n_articles = $n->children()->filterBy("template", "review");
		$reviews->add($n_articles);
	}

	foreach ($reviews as $review) {
		foreach ($review->keywords()->split() as $kw) {
			array_push($keywords, $kw);
		}
	}

	$keyword = param('keyword');
	if (empty($keyword) === false) {
		$reviews = $reviews->filterBy('keywords', $keyword, ',');
	}

	return [
		"reviews" => $reviews,
		"authors" => $authors,
		"keywords" => $keywords
	];
};
