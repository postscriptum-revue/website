<?php snippet("site/layout", slots: true) ?>

<?php slot("aside") ?>

<?php endslot() ?>

<?php slot("main") ?>
	<?php foreach (page("parutions")->children() as $issue) : ?>
		<?php snippet("site/issue-small", ["issue" => $issue]) ?>
	<?php endforeach ?>
<?php endslot() ?>