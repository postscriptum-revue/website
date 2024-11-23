<?php

return function () {

	$issues = page("numeros")->children()->listed()->flip();
	$last_issue = page("numeros")->children()->listed()->last();

	return [
		"issues" => $issues,
		"last_issue"=> $last_issue
	];
};
