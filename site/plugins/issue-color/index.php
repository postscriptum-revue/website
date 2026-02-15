<?php

require_once 'vendor/autoload.php';

use ColorThief\ColorThief;

\Kirby\Cms\App::plugin("postscriptum/issue-color", [
	"hooks" => [
		"file.create:after" => function (Kirby\Cms\File $file) {
			if ($file->template() == "cover") {
				updateIssueColor($file->page(), $file);
			}
		},
		"page.update:after" => function (Kirby\Cms\Page $newPage, Kirby\Cms\Page $oldPage) {
			if ($newPage->intendedTemplate()->name() !== "issue") return;
			$newCover = $newPage->cover()->toFile();
			$oldCover = $oldPage->cover()->toFile();
			if ($newCover && $newCover->id() !== ($oldCover ? $oldCover->id() : null)) {
				updateIssueColor($newPage, $newCover);
			}
		}
	]
]);

function updateIssueColor($page, $file)
{
	$palette = findIssuePalette($file);

	$biggest_difference = 0;
	$color = $palette[0];

	foreach ($palette as $c) {
		$difference = max($c) - min($c);
		if ($difference > $biggest_difference) {
			$biggest_difference = $difference;
			$color = $c;
		}
	}

	$page->update([
		"color" => "rgb($color[0], $color[1], $color[2])"
	]);
}

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
