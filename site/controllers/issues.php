<?php

return function () {

	$issues = page("parutions")->children()->listed()->flip();

	return [
		"issues" => $issues
	];
};
