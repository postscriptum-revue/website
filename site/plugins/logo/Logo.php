<?php

class Logo
{

	/**
	 * Generate a combination of styles for the issue's logo
	 * (one style for the letter "P" and another for "S")
	 * and save the style in "site.txt" and the issue's
	 * "page.txt".
	 */
	public function save($site, $page)
	{
		// Get the list of previous logos's style, and convert
		// it from JSON to a PHP associative array.
		$style_list = $site->logo_style_list()->toData("json");

		// Generate a random style until we find one that isn't
		// included in the array of previously generated logo styles.
		do {
			$style = $this->generateStyle();
		} while (in_array($style["both"], $style_list));

		// New pages don't have a $page->num() because they are drafts,
		// and thus have no number yet.
		$issue_num =
			$page->num() ?? page("parutions")->children()->count();

		// Add the new logo style to the list. 
		// `$style["both"]` is used to make the comparison easier.
		$style_list[$issue_num] = $style["both"];
		$style_list_json = json_encode($style_list);

		// Update the fields in both `site.txt` 
		// and the issue's `issue.txt`
		$site = $site->update([
			"logo_style_list" => $style_list_json
		]);
		$page->update([
			"logo_style_p" => $style["p"],
			"logo_style_s" => $style["s"]
		]);

		// Generate the PDFs for the issue's logo.
		$this->generateLogoFiles(
			$style["p"],
			$style["s"],
			$issue_num,
			$page->root(),
		);

		// $site is returned because $site->update() doesn't mutate
		// the original object, but rather returns the updated object.
		// Thus it's necessary to return the updated $site if we wish
		// to, say, generate multiple logos in a loop.
		return $site;
	}

	private function generateStyle()
	{
		// Styles need to be padded (ex. "01"") for CSS's 
		// stylistic set property to work correctly.
		$p = str_pad(rand(1, 20), 2, 0, STR_PAD_LEFT);
		$s = str_pad(rand(1, 20), 2, 0, STR_PAD_LEFT);

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

	/**
	 * Generate the files in different format for the issue's logo
	 * and save them in the page folder.
	 */
	private function generateLogoFiles($p_style, $s_style, $issue_num, $page_dir)
	{
		shell_exec(
			// Must cd beforehand because the script is called from
			// the website's root.
			"cd " . __DIR__ . " && " .
				"./compile-tex.sh " .
				"$p_style $s_style $issue_num $page_dir "
		);
	}
}
