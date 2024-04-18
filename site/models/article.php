<?php

use Kirby\Cms\Page;

class ArticlePage extends Page
{
	public function title_and_subtitle()
	{
		$title = parent::title()->smartypants();
		if (parent::formatted_title()->isNotEmpty()) {
			$title = parent::formatted_title()->smartypants();
		}

		if (
			!str_ends_with($title, "?")
			&& !str_ends_with($title, "!")
			&& !str_ends_with($title, ".")
			&& parent::subtitle() != ""
		) {
			$title .= "&nbsp;: ";
		}

		if (parent::subtitle() != "") {
			$title .= " " . parent::subtitle()->smartypants();
		}

		return $title;
	}
}
