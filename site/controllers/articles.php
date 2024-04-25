<?php

use Kirby\Cms\Pages;

return function () {
	$authors = [];
	$keywords = [];
	$articles = new Pages();
	$numeros = page("numeros")->children()->listed();

	foreach ($numeros as $n) {
		$n_articles = $n->children()->filterBy("template", "article");
		$articles->add($n_articles);
	}

	foreach ($articles as $article) {
		foreach ($article->keywords()->split() as $kw) {
			array_push($keywords, $kw);
		}
	}
	sort($keywords);

	$keyword = param('keyword');
	if (empty($keyword) === false) {
		$articles = $articles->filterBy('keywords', $keyword, ',');
	}

	return [
		"articles" => $articles,
		"authors" => $authors,
		"keywords" => $keywords
	];
};
