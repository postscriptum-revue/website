<?php

use Kirby\Cms\Pages;

return function ($site) {
	$interviews = $site->index()
					->filterBy('template', 'interview')->listed()
					->sort(fn($interview) => $interview->nonEmptyDate(), 'desc');

	$last_issue = page("numeros")->children()->listed()->last();

	return [
		"interviews" => $interviews,
		"last_issue" => $last_issue
	];
};
