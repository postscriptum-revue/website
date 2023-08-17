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

<body>
	<?php snippet("header") ?>
	<main>