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
		$published_issues_count = pages("numeros")->children()->count();
		$draft_issues_count = pages("numeros")->drafts()->count();
		$all_issues_count = $published_issues_count + $draft_issues_count;
		$issue_num =
			$page->num() ?? $all_issues_count;

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

		// Generate the SVGs for the issue's logo.
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

	static public function delete($issue_num, $site)
	{
		$style_list = $site->logo_style_list()->toData("json");
		unset($style_list[$issue_num]);
		$style_list_json = json_encode($style_list);

		$site = $site->update([
			"logo_style_list" => $style_list_json
		]);
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
	 * Generate SVG files for the issue's logo.
	 * The font is embedded as base64 so the SVGs are self-contained.
	 */
	private function generateLogoFiles($p_style, $s_style, $issue_num, $page_dir)
	{
		$font_path = __DIR__ . '/Mercure-Transcript.otf';
		$font_base64 = base64_encode(file_get_contents($font_path));

		$colors = ['black', 'white'];
		$versions = [
			'ps' => fn($num) => $this->logoText($p_style, 'P', $s_style, 'S'),
			'psnum' => fn($num) => $this->logoText($p_style, 'P', $s_style, 'S') . "<tspan>{$num}</tspan>",
			'postscriptum' => fn($num) => $this->logoText($p_style, 'P', $s_style, 'S', true),
		];

		foreach ($colors as $color) {
			foreach ($versions as $version => $textFn) {
				$filename = "logo-{$issue_num}-{$version}-{$color}.svg";
				$text = $textFn($issue_num);
				$svg = $this->buildSvg($font_base64, $text, $color);
				file_put_contents($page_dir . '/' . $filename, $svg);
			}
		}
	}

	private function logoText($p_style, $p_letter, $s_style, $s_letter, $full = false)
	{
		$p = "<tspan style=\"font-feature-settings: 'ss{$p_style}'\">{$p_letter}</tspan>";
		$s = "<tspan style=\"font-feature-settings: 'ss{$s_style}'\">{$s_letter}</tspan>";

		if ($full) {
			return "{$p}OST{$s}CRIPTUM";
		}
		return "{$p}{$s}";
	}

	private function buildSvg($font_base64, $text, $color)
	{
		$width = strlen(strip_tags($text)) > 2 ? 300 : 60;

		return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {$width} 40">
	<defs>
		<style>
			@font-face {
				font-family: "Mercure Transcript";
				src: url("data:font/otf;base64,{$font_base64}");
			}
		</style>
	</defs>
	<text
		x="50%" y="50%"
		dominant-baseline="central"
		text-anchor="middle"
		font-family="Mercure Transcript"
		font-size="28"
		fill="{$color}">{$text}</text>
</svg>
SVG;
	}
}
