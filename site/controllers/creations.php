<?php

use Kirby\Cms\Pages;

return function ($site) {
	$creations = $site->index()
		->filterBy('template', 'creation')->listed()
		->sortBy('date', 'desc');

	$last_issue = page("numeros")->children()->listed()->last();

	return [
		"creations" => $creations,
		"last_issue" => $last_issue
	];
};
