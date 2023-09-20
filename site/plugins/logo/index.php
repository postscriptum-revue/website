<?php

require __DIR__ . "/Logo.php";

Kirby::plugin("postscriptum/logo", [
	"hooks" => [
		"page.create:after" => function ($page) {
			if ($page->template() == "issue") {
				$logo = new Logo();
				$logo->save($this->site(), $page);
			}
		}
	]
]);
