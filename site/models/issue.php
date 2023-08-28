<?php

class IssuePage extends Page
{
	public function date()
	{
		$formatted_date = parent::date()->toDate("MMMM Y");
		return ucfirst($formatted_date);
	}
}
