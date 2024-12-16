<?php
$citation_authors = "";
$authors = $page->authors()->toStructure();
foreach ($authors as $i => $author) {
	$citation_authors .= $author->name();
	if ($i < count($authors) - 1) {
		$citation_authors .= ", ";
	}
}
?>

<p class="article-citation">
	<?= $citation_authors ?>.
	<?= $page->title_and_subtitle() ?>,

	Post-Scriptum<?php if ($page->parent()->template() == "issue"): ?>
		<?= ' ' . $page->parent()->num() ?><?php endif ?>.
	(<?= formatDate($page->date(), 'MMMM yyyy', false) ?>).
</p>