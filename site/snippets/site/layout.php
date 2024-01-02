<?php
// TODO: Still relevant?
$color = $issue_color ??  $page->color();

?>

<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="UTF-8">
	<meta
		name="viewport"
		content="width=device-width, initial-scale=1.0"
	>
	<link rel="stylesheet" href="/assets/css/style.css">
	<!-- TODO: Group in one script -->
	<script src="/assets/scripts/logo.js" defer></script>
	<script src="/assets/scripts/mobile.js" defer></script>
	<script src="/assets/scripts/footnotes.js" defer></script>
	<title>
		<?php
		if ($page->template() == "issue") {
			echo "PS{$page->num()} | {$page->title()}";
		} elseif ($page->template() == "article"){
			echo "PS{$page->parent()->num()} | {$page->title()}";
		} else {
			echo "PS | {$page->title()}";
		}
		?>
	</title>
</head>

<body style="--issue-color: <?= $color ?>">
	<aside class="site-aside">
		<div class="site-aside__content-wrapper">
			<?= $slots->aside() ?>
		</div>
	</aside>
	<?php snippet("site/header") ?>
	<main class="<?= $page->template() . "-template-main" ?>">
		<?= $slots->main() ?>
	</main>
	<footer class="site-footer"></footer>
</body>

</html>
