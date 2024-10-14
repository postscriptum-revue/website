<?php snippet("site/layout", slots: true); ?>

<?php slot("aside"); ?>
<?php endslot(); ?>

<?php slot("main"); ?>
<ul style="grid-column: -1 / 1">
	<?php foreach ($last_issue->files() as $file): ?>
		<li>
			<a href="<?= $file->url() ?>">
				<?= $file->filename() ?>
			</a>
		</li>
	<?php endforeach; ?>
</ul>
<?php endslot(); ?>
