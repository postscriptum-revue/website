<?php

require_once 'vendor/autoload.php';

use ColorThief\ColorThief;

\Kirby\Cms\App::plugin("postscriptum/issue-color", [
	"hooks" => [
		// TODO : Right now, this is only triggered when files
		// are uploaded. There doesn't seem to be a hook for a
		// file is selected in the panel.
		"file.create:after" => function (Kirby\Cms\File $file) {
			if ($file->template() == "cover") {
				$palette  = findIssuePalette($file);

				$biggest_difference = 0;
				$color = $palette[0];

				// Find the most vivid color from the image's palette. 
				foreach ($palette as $c) {
					$difference = max($c) - min($c);
					if ($difference > $biggest_difference) {
						$biggest_difference = $difference;
						$color = $c;
					}
				}

				$file->page()->update([
					"color" => "rgb($color[0], $color[1], $color[2])"
				]);
			}
		}
	]
]);

function findIssuePalette($file)
{
	$palette = ColorThief::getPalette(
		$file->root()
	);

	foreach ($palette as &$color) {
		$color = accessibilize($color);
	}

	return $palette;
}

function accessibilize($color)
{
	$contrast = findContrast($color[0], $color[1], $color[2]);
	$minContrast = 4.1;

	while ($contrast < $minContrast) {
		foreach ($color as &$channel) {
			$channel < 5 ? $channel = 0 : $channel -= 5;
		}

		$contrast = findContrast($color[0], $color[1], $color[2]);
	}

	return $color;
}

function findLuminance($r, $g, $b)
{
	return 0.2126 *  linearize($r) +
		0.7152 *  linearize($g) +
		0.0722 *  linearize($b);
}

function linearize($channel)
{

	if ($channel <= 0.04045) {
		return $channel / 12.92;
	} else {
		return pow((($channel + 0.055) / 1.055), 2.4);
	}
}

function findContrast($r, $g, $b)
{
	$r /= 255;
	$g /= 255;
	$b /= 255;

	$l = findLuminance($r, $g, $b);

	return (1.0 + 0.05) / ($l + 0.05);
}
