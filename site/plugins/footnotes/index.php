<?php

\Kirby\Cms\App::plugin("postscriptum/footnotes", [
	"blocksMethods" => [
		"collectFootnotes" => function () {
			$new_blocks = [];
			foreach ($this as $block) {
				$block = $block->toArray();
				// Image blocks don't have text.
				if (isset($block["content"]["text"])) {
					$block["content"]["text"] =
						Footnotes::collect($block["content"]["text"]);
				}
				array_push($new_blocks, new Kirby\Cms\Block($block));
			}
			return new Kirby\Cms\Blocks($new_blocks);
		}
	],
	"pageMethods" => [
		"footnotes" => function () {
			$html = "<ol class='footnote-list'>";
			foreach (Footnotes::$all as $i => $fn) {
				$fn_index = $i + 1;
				$html .= "<li id='footnote-{$fn_index}' class='footnote-list__footnote'>
                    <a href='#footnote-ref-{$fn_index}' class='footnote-list__backlink'>
                        <span class='footnote-list__footnote-number'>{$fn_index}</span>
                    </a>
                    {$fn}
                </li>";
			}
			$html .= "</ol>";
			return $html;
		}
	]
]);

class Footnotes
{
	public static $all = [];

	public static function collect($text)
	{
		$footnotes = self::parse($text);
		$text = self::strip($text, $footnotes["raw"]);
		self::$all = array_merge(self::$all, $footnotes["text"]);
		return $text;
	}

	private static function parse($text)
	{
		$pattern = "/\[\[(.*?)\]\]/s";
		preg_match_all($pattern, $text, $matches);
		return ["raw" => $matches[0], "text" => $matches[1]];
	}

	private static function strip($text, $footnotes_raw)
	{
		foreach ($footnotes_raw as $i => $fn_raw) {
			$fn_index = count(self::$all) + $i + 1;
			$html =
				'<a id="footnote-ref-' . $fn_index . '" class="footnote-ref" href="#footnote-' .
				$fn_index .
				'">' . $fn_index . '</a>';
			$text = str_replace($fn_raw, $html, $text);
		}
		return $text;
	}
}
