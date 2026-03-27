<?php
$schema = null;

if (in_array($page->template()->name(), ['article', 'interview', 'review', 'creation'])) {
	$authors = [];
	foreach ($page->authors()->toStructure() as $author) {
		$authors[] = [
			"@type" => "Person",
			"name" => $author->name()->value()
		];
	}

	$schema = [
		"@context" => "https://schema.org",
		"@type" => "ScholarlyArticle",
		"headline" => $page->title()->value(),
		"author" => $authors,
		"datePublished" => $page->issued_date()->isNotEmpty()
			? $page->issued_date()->toDate('Y-m-d')
			: ($page->parent() ? $page->parent()->issued_date()->toDate('Y-m-d') : null),
		"publisher" => [
			"@type" => "Organization",
			"name" => "Post-Scriptum"
		],
		"url" => $page->url(),
		"isPartOf" => [
			"@type" => "Periodical",
			"name" => "Post-Scriptum"
		]
	];

	if ($page->abstract_fr()->isNotEmpty()) {
		$schema["abstract"] = strip_tags($page->abstract_fr()->value());
	}

	if ($page->keywords()->isNotEmpty()) {
		$schema["keywords"] = $page->keywords()->split();
	}

	if ($page->parent() && $page->parent()->template()->name() === 'issue') {
		$schema["isPartOf"] = [
			"@type" => "PublicationIssue",
			"issueNumber" => $page->parent()->num(),
			"name" => $page->parent()->title()->value(),
			"isPartOf" => [
				"@type" => "Periodical",
				"name" => "Post-Scriptum"
			]
		];
	}

} elseif ($page->template()->name() === 'issue') {
	$schema = [
		"@context" => "https://schema.org",
		"@type" => "PublicationIssue",
		"issueNumber" => $page->num(),
		"name" => $page->title()->value(),
		"datePublished" => $page->issued_date()->toDate('Y-m-d'),
		"url" => $page->url(),
		"isPartOf" => [
			"@type" => "Periodical",
			"name" => "Post-Scriptum"
		]
	];
}

if ($schema): ?>
<script type="application/ld+json"><?= json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?></script>
<?php endif ?>
