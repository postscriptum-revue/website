<?php snippet("base", slots: true) ?>

<?php slot("aside") ?>

<?php endslot() ?>

<?php slot("main") ?>
<section class="latest-section latest-issues">
	<?php foreach (page("issues")->children() as $issue) : ?>
		<a href="<?= $issue->url() ?>">
			<article>
				<div>
					<?= $issue->num() ?>
				</div>
				<hgroup>
					<p><?= $issue->date() ?></p>
					<h3><?= $issue->title() ?></h3>
				</hgroup>
			</article>
		</a>
	<?php endforeach ?>
</section>
<?php endslot() ?>