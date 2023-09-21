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
		switch ($page->template()):
			case 'home': echo "Post-Scriptum - Accueil";break;
			case 'issues': echo "Post-Scriptum - Numéros de parution";break;
			case 'articles': echo "Post-Scriptum - Articles";break;
			case 'issue': case 'article': echo $page->title();break;
			default: echo "Post-Scriptum";
		endswitch
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