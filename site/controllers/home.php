<?php

return function () {
	$last_issue = page("parutions")->children()->last();

	return [
		"last_issue" => $last_issue
	];
};
