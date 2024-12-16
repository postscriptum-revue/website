<?php snippet("site/layout", slots: true) ?>

<?php if ($page->intro_bibliography()->isNotEmpty()): ?>

	<?php slot("aside_button") ?>Bibliographie<?php endslot() ?>

	<?php slot("aside") ?>
	<section class="site-aside__section article-bibliography">
		<?= $page->intro_bibliography() ?>
	</section>
	<?php endslot() ?>
<?php else: ?>
	<?php slot("aside_button") ?>Dernier Numéro<?php endslot() ?>
	<?php slot("aside") ?>
	<?php snippet("site/issue-aside", $last_issue) ?>
	<?php endslot() ?>
<?php endif; ?>

<?php slot("main") ?>

<?php if ($page->cover()->isNotEmpty()): ?>
	<img class="news-cover" src="<?= $page->cover()->toFile()->url() ?>">
<?php endif ?>
<section class="text-section">
	<hgroup class="text-section-hgroup">
		<p class="text-section-hroup__news-type">
			<?= $page->blueprint()->title() ?>
		</p>
		<h1 class="text-section-hgroup__title">
			<?= $page->issue_title() ?>
		</h1>
		<?php if ($page->issue_subtitle()): ?>
			<p><?= $page->issue_subtitle() ?></p>
		<?php endif ?>
	</hgroup>
	<?= $page->intro_text()->toBlocks() ?>
</section>

<dl class="issue-credits">
	<?php if ($page->cover()->isNotEmpty()): ?>
		<dt class="issue-credits__term">Image de couverture</dt>
		<dd class="issue-credits__description">
			<?= $page->cover()->toFile()->credit()->smartypants() ?>
		</dd>
	<?php endif ?>
	<dt class="issue-credits__term">Éditeur·rice(s)</dt>
	<dd class="issue-credits__description">
		<?= $page->issue_editors()->smartypants() ?>
	</dd>
	<dt class="issue-credits__term">Date limite</dt>
	<dd class="issue-credits__description">
		<?= $page->submit_date()->toDate("d MMMM y") ?>
	</dd>
	<dt class="issue-credits__term">Date de parution</dt>
	<dd class="issue-credits__description">
		<?= $page->issue_date()->toDate("d MMMM y") ?>
	</dd>
</dl>

<?php endslot() ?>