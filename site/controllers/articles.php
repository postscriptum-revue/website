<?php

return function () {
	$authors = [];
	$keywords = [];
	$articles = new Pages();
	$parutions = page("parutions")->children();

	foreach ($parutions as $parution) {
		$parution_articles = $parution->children();
		$articles->add($parution_articles);
	}

	foreach ($articles as $article) {
		foreach ($article->keywords()->split() as $kw) {
			array_push($keywords, $kw);
		}
	}

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
