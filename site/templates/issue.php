<?php snippet("site/layout", slots: true) ?>

<?php slot("aside") ?>
<?php snippet("issue/toc") ?>
<?php endslot() ?>

<?php slot("main") ?>

<?php snippet("issue/header") ?>

<section class="text-section issue-text">
	<hgroup class="text-section-hgroup">
		<?php if ($page->intro_title()->isNotEmpty()) : ?>
			<p>Texte de présentation</p>
			<h2 class="text-section-hgroup__title">
				<?= $page->intro_title() ?>
			</h2>
			<?php if ($page->intro_subtitle()) : ?>
				<p><?= $page->intro_subtitle() ?></p>
			<?php endif ?>
		<?php else : ?>
			<h2 class="text-section-hgroup__title">
				Texte de présentation
			</h2>
		<?php endif ?>
	</hgroup>
	<?= $page->intro_text()->toBlocks() ?>
</section>

<dl class="issue-credits">
	<!-- TODO: Normalize terms. -->
	<?php if ($credits_cover = $page->cover()->toFile()->credit()->isNotEmpty())
		echo '<dt class="issue-credits__term">Image de couverture</dt>
			  <dd class="issue-credits__description">' .  $credits_cover . '</dd>' ?>
	<?php if ($credits_intro = $page->credit_intro()->isNotEmpty())
		echo '<dt class="issue-credits__term">Texte de présentation</dt>
			  <dd class="issue-credits__description">' . $credits_intro . '</dd>' ?>
	<dt class="issue-credits__term">Éditeur·rice(s)</dt>
	<dd class="issue-credits__description">
		<?= $page->credit_editors() ?>
	</dd>
	<dt class="issue-credits__term">Révision</dt>
	<dd class="issue-credits__description">
		<?= $page->credit_revision() ?>
	</dd>
	<dt class="issue-credits__term">Mise en ligne</dt>
	<dd class="issue-credits__description">
		<?= $page->credit_webmaster() ?>
	</dd>
</dl>

<?php endslot() ?>