<?php

class Logo
{

	/*
	* Generate a combination of styles for the issue's logo
	* (one style for the letter "P" and another for "S")
	* and save the style in "site.txt" and the issue's
	* "page.txt".
	*/
	public function save($site, $page)
	{
		$style_list = $site->logo_style_list();
		$style_list_array = $style_list->split(",");

		// Generate a random style until we find one that isn't
		// included in the array of previously generated logo styles.
		do {
			$style = $this->generateStyle();
		} while (in_array($style["both"], $style_list_array));

		$site->update([
			"logo_style_list" => $style_list . ", " . $style["both"]
		]);

		$page->update([
			"logo_style_p" => $style["p"],
			"logo_style_s" => $style["s"]
		]);
	}

	private function generateStyle()
	{
		// Styles need to be padded (ex. "01"") for CSS's 
		// stylistic set property to work correctly.
		$p = str_pad(rand(0, 20), 2, 0, STR_PAD_LEFT);
		$s = str_pad(rand(0, 20), 2, 0, STR_PAD_LEFT);

		// Both styles (for letters "P" and "S") are stored together
		// to make it easier to verify we don't use the same
		// combination twice.
		$both = $p . $s;

		return [
			"p" => $p,
			"s" => $s,
			"both" => $both
		];
	}
}
