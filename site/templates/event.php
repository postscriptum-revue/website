<?php snippet("site/layout", slots: true) ?>

<?php slot("aside_button") ?>Bibliographie<?php endslot() ?>

<?php slot("aside") ?>
<?php if (($bibliography = $page->intro_bibliography())->isNotEmpty()) : ?>
	<section class="site-aside__section article-bibliography">
		<?= $bibliography ?>
	</section>
<?php else : ?>
	<?php snippet("site/issue-aside", $last_issue) ?>
<?php endif ?>
<?php endslot() ?>

<?php slot("main") ?>
<?php if ($page->cover()->isNotEmpty()): ?>
	<img
		class="news-cover"
		src="<?= $page->cover()->toFile()->url() ?>"
	>
<?php endif ?>
<section class="text-section">
	<hgroup class="text-section-hgroup">
		<p class="text-section-hroup__news-type">
			<?= $page->blueprint()->title() ?>
		</p>
		<h1 class="text-section-hgroup__title">
			<?= $page->title() ?>
		</h1>
		<?php if ($page->subtitle()) : ?>
			<p><?= $page->subtitle() ?></p>
		<?php endif ?>
	</hgroup>
	<?= $page->intro_text()->toBlocks() ?>
</section>

<dl class="issue-credits">
	<?php if ($page->cover()->isNotEmpty()) : ?>
		<dt class="issue-credits__term">Image de couverture</dt>
		<dd class="issue-credits__description">
			<?= $page->cover()->toFile()->credit()->smartypants() ?>
		</dd>
	<?php endif ?>
	<dt class="issue-credits__term">Organisateur·rice(s)</dt>
	<dd class="issue-credits__description">
		<?= $page->organizers()->smartypants() ?>
	</dd>
	<dt class="issue-credits__term">Date</dt>
	<dd class="issue-credits__description">
		<?= $page->date_start()->toDate("d MMMM") ?>
		–
		<?= $page->date_end()->toDate("d MMMM y") ?>
	</dd>
</dl>

<?php endslot() ?>
