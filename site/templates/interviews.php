<?php snippet("site/layout", slots: true) ?>

<?php slot("aside_button") ?>Dernier numéro<?php endslot() ?>

<?php slot("aside") ?>
<?php snippet("site/issue-aside", $last_issue) ?>
<?php endslot() ?>

<?php slot("main") ?>
<?php snippet("site/toc-grid", [
	"toc_pages" => $interviews
]) ?>
<?php endslot() ?>