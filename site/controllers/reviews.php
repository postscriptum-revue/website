<?php

use Kirby\Cms\Pages;

return function ($site) {
	$authors = [];
	$keywords = [];
	$reviews = $site->index()->filterBy('template', 'review');

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
