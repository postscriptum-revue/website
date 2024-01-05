<?php

return function () {

	$issues = page("numeros")->children()->listed()->flip();

	return [
		"issues" => $issues
	];
};
