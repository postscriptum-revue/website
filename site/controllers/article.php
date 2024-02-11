<?php

return function ($page) {
	$issue_color = $page->parent()->color();

	return [
		"issue_color" => $issue_color,
	];
};
