<?php

use Kirby\Cms\Page;

class IssuePage extends Page
{
	public function fmt_date()
	{
		$formatted_date = parent::date()->toDate("MMMM yyyy");
		return ucfirst($formatted_date);
	}

	// Can't format title here because the panel it causes issues
	// in the Panel. For instance, it displays the Unicode code for
	// the apostrophe instead of the apostrophe itself.

	public function subtitle()
	{
		$formatted_subtitle = parent::subtitle()->smartypants();
		return $formatted_subtitle;
	}
}
