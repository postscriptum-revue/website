<?php

return function () {
	$last_issue = page("numeros")->children()->listed()->last();

	return [
		"last_issue"=> $last_issue
	];
};
