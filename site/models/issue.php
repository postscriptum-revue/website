<?php

use Kirby\Cms\Page;

class IssuePage extends Page
{
	public function date()
	{
		$formatted_date = parent::date()->toDate("MMMM Y");
		return ucfirst($formatted_date);
	}

	public function subtitle()
	{
		$formatted_subtitle = parent::subtitle()->smartypants();
		return $formatted_subtitle;
	}
}
