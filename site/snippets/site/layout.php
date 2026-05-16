<?php
$color = $issue_color ?? $page->color();

if (!isset($meta_authors)) {
	$meta_authors = "";
}

if (!isset($meta_description)) {
	$meta_description = "";
}

if ($page->template() == "issue") {
	$meta_title = "PS{$page->num()} | {$page->title()}";
} elseif ($page->template() == "article") {
	$meta_title = "PS{$page->parent()->num()} | {$page->title()}";
} else {
	$meta_title = "PS | {$page->title()}";
}

$meta_image = "";
if ($page->cover() && $page->cover()->toFile()) {
	$meta_image = $page->cover()->toFile()->url();
} elseif ($page->parent() && $page->parent()->cover() && $page->parent()->cover()->toFile()) {
	$meta_image = $page->parent()->cover()->toFile()->url();
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
	<link rel="icon" href="/assets/images/favicon.svg" type="image/svg+xml">
	<link rel="canonical" href="<?= $page->url() ?>">
	<meta property="og:title" content="<?= esc($meta_title) ?>">
	<meta property="og:description" content="<?= esc($meta_description) ?>">
	<meta property="og:url" content="<?= $page->url() ?>">
	<meta property="og:type" content="<?= $page->template() == 'article' ? 'article' : 'website' ?>">
	<meta property="og:locale" content="fr_CA">
	<meta property="og:site_name" content="Post-Scriptum">
	<?php if ($meta_image): ?>
		<meta property="og:image" content="<?= $meta_image ?>">
	<?php endif ?>
	<link rel="stylesheet" href="/assets/css/style.css">
	<script src="/assets/scripts/script.js" defer></script>
	<?php snippet("site/structured-data") ?>
	<title><?= esc($meta_title) ?></title>
</head>

<body
	style="--issue-color: <?= $color ?>"
	class="body--template-<?= $page->template() ?>">
	<a class="skip-link" href="#main-content">Passer au contenu</a>
	<aside class="site-aside" id="site-aside">
		<button
			class="site-aside__mobile-button"
			aria-label="Ouvrir le menu latéral"
			aria-expanded="false"
			aria-controls="site-aside">
			<span class="site-aside__mobile-button-icon">
				<?= $slots->aside_button() ?>
			</span>
		</button>
		<div class="site-aside__content-wrapper">
			<?= $slots->aside() ?>
		</div>
	</aside>
	<?php snippet("site/header") ?>
	<main id="main-content">
		<?= $slots->main() ?>
	</main>
	<footer class="site-footer">
		<p>&copy; <?= date('Y') ?> Post-Scriptum</p>
		<nav class="site-footer__nav">
			<a href="<?= page('a-propos')->url() ?>">À propos</a>
		</nav>
	</footer>
	<?php snippet("site/lightbox") ?>
</body>

</html>