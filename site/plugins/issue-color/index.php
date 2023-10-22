<?php

\Kirby\Cms\App::plugin("postscriptum/issue-color", [
	"hooks" => [
		// Make sure it works when file are changed
		"file.create:after" => function (Kirby\Cms\File $file) {
			if ($file->template() == "cover") {

			}
			// file_put_contents(__DIR__ . "/log.txt", $file->template());
		}
	]
]);
