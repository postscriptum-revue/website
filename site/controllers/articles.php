<?php

return function () {
	$articles = new Pages();
	$parutions = page("parutions")->children();

	foreach ($parutions as $parution) {
		$parution_articles = $parution->children();
		$articles->add($parution_articles);
	}

	return [
		"articles" => $articles
	];
};
