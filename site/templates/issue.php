<?php snippet("site/layout", slots: true) ?>

<?php slot("aside_button") ?>Table des matières<?php endslot() ?>

<?php slot("aside") ?>
<?php snippet("site/toc", ["toc_pages" => $page->children(), "hiddenDate" => true]) ?>
<?php endslot() ?>

<?php slot("main") ?>

<?php snippet("issue/header") ?>

<section class="text-section issue-text">
	<hgroup class="text-section-hgroup">
		<?php if ($page->intro_title()->isNotEmpty()): ?>
			<p>Texte de présentation</p>
			<h2 class="text-section-hgroup__title">
				<?= $page->intro_title() ?>
			</h2>
			<?php if ($page->intro_subtitle()): ?>
				<p><?= $page->intro_subtitle() ?></p>
			<?php endif ?>
		<?php else: ?>
			<h2 class="text-section-hgroup__title">
				Texte de présentation
			</h2>
		<?php endif ?>
	</hgroup>
	<section class="text-section">
		<?= $page->intro_text()->toBlocks()->collectFootnotes() ?>
		<?php if ($page->bibliography()->isNotEmpty()): ?>
			<ul style="margin-top: 3rem;">
				<li>
					<h3 style="margin-bottom: 0.5rem;">Bibliographie</h3>
					<?= $page->bibliography()->toBlocks()->collectFootnotes() ?>
				</li>
			</ul>
		<?php endif ?>
	</section>

	<?php if ($page->footnotes()): ?>
	<section class="footnotes">
		<?= $page->footnotes() ?>
	</section>
	<?php endif ?>

</section>

<dl class="issue-credits">
	<!-- TODO: Normalize terms. -->
	<?php if ($cover->credit()->isNotEmpty()): ?>
		<dt class="issue-credits__term">Image de couverture</dt>
		<dd class="issue-credits__description">
			<?= $cover->credit()->smartypants() ?>
		</dd>
	<?php endif ?>
	<?php if ($page->credit_intro()->isNotEmpty()): ?>
		<dt class="issue-credits__term">Texte de présentation</dt>
		<dd class="issue-credits__description">
			<?= $page->credit_intro()->smartypants() ?>
		</dd>
	<?php endif ?>
	<dt class="issue-credits__term">Éditeur·rice(s)</dt>
	<dd class="issue-credits__description">
		<?= $page->credit_editors()->smartypants() ?>
	</dd>
	<dt class="issue-credits__term">Révision</dt>
	<dd class="issue-credits__description">
		<?= $page->credit_revision()->smartypants() ?>
	</dd>
	<dt class="issue-credits__term">Mise en ligne</dt>
	<dd class="issue-credits__description">
		<?= $page->credit_webmaster()->smartypants() ?>
	</dd>
</dl>

<?php endslot() ?>