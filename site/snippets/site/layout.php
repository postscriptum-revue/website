<?php
// TODO: Still relevant?
$color = $issue_color ??  $page->color();

if (!isset($meta_authors)) {
	$meta_authors = "";
}

if (!isset($meta_description)) {
	$meta_description = "";
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="UTF-8">
	<meta
		name="viewport"
		content="width=device-width, initial-scale=1.0">
	<meta name="author" content="<?= $meta_authors ?>">
	<meta name="description" content="<?= $meta_description ?>">
	<link rel="stylesheet" href="/assets/css/style.css">
	<!-- TODO: Group in one script -->
	<script src="/assets/scripts/logo.js" defer></script>
	<script src="/assets/scripts/mobile.js" defer></script>
	<script src="/assets/scripts/footnotes.js" defer></script>
	<script src="/assets/scripts/lightbox.js" defer></script>
	<title>
		<?php
		if ($page->template() == "issue") {
			echo "PS{$page->num()} | {$page->title()}";
		} elseif ($page->template() == "article") {
			echo "PS{$page->parent()->num()} | {$page->title()}";
		} else {
			echo "PS | {$page->title()}";
		}
		?>
	</title>
</head>

<body
	style="--issue-color: <?= $color ?>"
	class="body--template-<?= $page->template() ?>">
	<aside class="site-aside">
		<button class="site-aside__mobile-button">
			<span class="site-aside__mobile-button-icon">
				<?= $slots->aside_button() ?>
			</span>
		</button>
		<div class="site-aside__content-wrapper">
			<?= $slots->aside() ?>
		</div>
	</aside>
	<?php snippet("site/header") ?>
	<main>
		<?= $slots->main() ?>
	</main>
	<footer class="site-footer"></footer>
	<?php snippet("site/lightbox") ?>
</body>

</html>