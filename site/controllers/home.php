<?php

use Kirby\Cms\Pages;

return function () {

	$news_page = page("actualites");
	$all_news = new Pages();

	foreach ($news_page->children() as $news_section) {
		$all_news->add($news_section->children());
	}

	$last_issue = page("parutions")->children()->last();
	$latest_issues = page("parutions")
		->children()->listed()->flip()->slice(1, 3);

	return [
		"last_issue" => $last_issue,
		"latest_issues" => $latest_issues,
		"all_news" => $all_news
	];
};
