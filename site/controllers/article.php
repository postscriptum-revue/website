<?php

return function ($page) {
	$issue_color = $page->parent()->color();

	$citation = "";
	$authors = $page->authors()->toStructure();
	foreach ($authors as $i => $author) {
		$citation .= $author->name();
		if ($i < count($authors) - 1) {
			$citation .= ", ";
		}
	}
	$citation .= ". ";
	$citation .= "« {$page->title()} : {$page->subtitle()} », ";
	$citation .= "Post-Scriptum {$page->parent()->num()}. ";
	$citation .= "({$page->parent()->date()}).";

	return [
		"issue_color" => $issue_color,
		"citation" => $citation
	];
};
