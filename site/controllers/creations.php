<?php

use Kirby\Cms\Pages;

return function ($site) {
	$creations = $site->index()
		->filterBy('template', 'creation')->listed()
		->sortBy(fn($p) => $p->nonEmptyDate()->toDate(), SORT_DESC);

	$last_issue = page("numeros")->children()->listed()->last();

	return [
		"creations" => $creations,
		"last_issue" => $last_issue
	];
};
