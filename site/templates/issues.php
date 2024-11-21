<?php snippet("site/layout", slots: true) ?>

<?php slot("aside") ?>
<?php snippet("site/issue-aside", $last_issue) ?>
<?php endslot() ?>

<?php slot("main") ?>
	<?php foreach ($issues as $issue) : ?>
		<?php snippet("site/issue-small", ["issue" => $issue]) ?>
	<?php endforeach ?>
<?php endslot() ?>