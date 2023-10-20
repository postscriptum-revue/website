<a href="/" class="site-header__logo">
	<?php if ($page->template() == "issue") : ?>
		<span style="
			font-feature-settings: 
				'ss<?= $page->logo_style_p() ?>';
		">P</span><span style="
			font-feature-settings: 
				'ss<?= $page->logo_style_s() ?>';
		">S</span><?= $page->num() ?>
	<?php elseif ($page->template() == "article") : ?>
		<span style="
			font-feature-settings: 
				'ss<?= $page->parent()->logo_style_p() ?>';
		">P</span><span style="
			font-feature-settings: 
				'ss<?= $page->parent()->logo_style_s() ?>';
		">S</span><?= $page->parent()->num() ?>
	<?php else : ?>
		<span style="
			font-feature-settings: 
				'ss<?= page("parutions")->children()->last()
					->logo_style_p() ?>';
		">P</span>OST<span style="
			font-feature-settings: 
				'ss<?= page("parutions")->children()->last()
					->logo_style_s() ?>';
		">S</span>CRIPTUM
	<?php endif ?>
</a>