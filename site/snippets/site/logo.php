<?php

$logo_page;
switch ($page->template()) {
	case "issue":
		$logo_page = $page;
	case "article":
		$logo_page = $page->parent();
	default:
		$logo_page = page("numeros")->children()->last();
}

$fontstyle = "font-feature-settings: 'ss";
$s_fontstyle = $fontstyle . $logo_page->logo_style_s() . "'";
$p_fontstyle = $fontstyle . $logo_page->logo_style_p() . "'";
?>

<a href="/" class="site-header__logo">
	<span class="site-header__logo-ps" style="<?= $p_fontstyle ?>">P</span>
	<span class="site-header__logo-ostcriptum">OST</span>
	<span class="site-header__logo-ps" style="<?= $s_fontstyle ?>">S</span>
	<span class="site-header__logo-ostcriptum">CRIPTUM</span>
	<span class="site-header__logo-num"><?= $logo_page->num() ?></span>
</a>
