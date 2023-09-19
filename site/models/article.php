<?php

use Kirby\Cms\Page;

class ArticlePage extends Page
{
	public function subtitle()
	{
		$formatted_subtitle = parent::subtitle()->smartypants();
		return $formatted_subtitle;
	}
}
