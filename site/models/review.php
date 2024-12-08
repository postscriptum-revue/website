<?php

use Kirby\Cms\Page;

class ReviewPage extends Page
{

	public function fmt_date()
	{
		$formatted_date = parent::date()->toDate("MMMM Y");
		if (!$formatted_date) return $this->parent()->fmt_date();
		return ucfirst($formatted_date);
	}

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
			$title .= parent::subtitle()->smartypants();
		}

		return $title;
	}

	public function nonEmptyDate()
	{
		$nonEmptyDate = $this->date();
		if ($nonEmptyDate == '') {
			$nonEmptyDate = $this->parent()->date();
		}

		return $nonEmptyDate;
	}
}
