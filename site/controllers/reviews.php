<?php

use Kirby\Cms\Pages;

return function ($site) {
	$reviews = $site->index()
		->filterBy('template', 'review')->listed()
		->sortBy(fn($p) => $p->nonEmptyDate()->toDate(), SORT_DESC, 'num', 'asc');

	$last_issue = page("numeros")->children()->listed()->last();


	return [
		"reviews" => $reviews,
		"last_issue" => $last_issue,
	];
};
