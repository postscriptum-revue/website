<?php

return function () {
	$last_issue = page("parutions")->children()->last();
	$latest_issues = page("parutions")->children()->flip()->slice(1, 3);

	return [
		"last_issue" => $last_issue,
		"latest_issues" => $latest_issues
	];
};
