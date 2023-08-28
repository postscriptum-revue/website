<?php

$color = $issue_color ??  $page->color();

?>

<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="/assets/css/style.css">
	<title>
		<?php
		if ($page->template() == "home") {
			echo "Post-Scriptum";
		} else {
			// TODO: Display "PS12" when in an issue or an article.
			echo "PS";
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
	<main>
		<?= $slots->main() ?>
	</main>
</body>

</html>