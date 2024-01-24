<?php

require __DIR__ . "/Logo.php";

\Kirby\Cms\App::plugin("postscriptum/logo", [
	"hooks" => [
		"page.create:after" => function ($page) {
			if ($page->template() == "issue") {
				$logo = new Logo();
				$logo->save($this->site(), $page);
			}
		},
		"page.delete:after" => function ($page) {
			if ($page->template() == "issue") {
				Logo::delete($page->num(), $this->site());
			}
		}
	]
]);
