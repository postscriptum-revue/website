<?php

// TODO: Replace with real logic.
$p_ss = random_int(0, 1) . random_int(0, 9);
$s_ss = random_int(0, 1) . random_int(0, 9);

?>

<a href="/" class="site-header__logo">
	<?php if ($page->template() == "home") : ?>
		<span style="font-feature-settings: 'ss<?= $p_ss ?>';">P</span>OST<span style="font-feature-settings: 'ss<?= $s_ss ?>'">S</span>CRIPTUM
	<?php else : ?>
		<span style="font-feature-settings: 'ss<?= $p_ss ?>';">P</span><span style="font-feature-settings: 'ss<?= $s_ss ?>'">S</span>
	<?php endif ?>
</a>