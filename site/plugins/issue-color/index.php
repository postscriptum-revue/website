<?php

require_once 'vendor/autoload.php';

use ColorThief\ColorThief;

\Kirby\Cms\App::plugin("postscriptum/issue-color", [
	"hooks" => [
		// TODO Make sure it works when file are changed
		"file.create:after" => function (Kirby\Cms\File $file) {
			if ($file->template() == "cover") {
				$dominant_color = ColorThief::getColor(
					$file->root(),
					outputFormat: "hex"
				);
				file_put_contents(__DIR__ . "/log.txt", $dominant_color);
				$file->page()->update([
					"color" => $dominant_color
				]);
			}
		}
	]
]);
