<?php

use Kirby\Cms\Pages;

return function ($site) {
	$reviews = $site->index()
		->filterBy('template', 'review')->listed()
		->sort('issued_date', 'desc');

	$last_issue = page("numeros")->children()->listed()->last();


	return [
		"reviews" => $reviews,
		"last_issue" => $last_issue,
	];
};
