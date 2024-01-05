<?php snippet("site/layout", slots: true) ?>

<?php slot("aside") ?>

<h2 class="site-aside__title">Logo</h2>
<ul>
	<?php foreach (page("numeros")->children()->flip() as $issue) : ?>
		<li>
			<details class="details">
				<summary class="summary">
					PS<?= $issue->num() ?>
				</summary>
				<!-- TODO: Remove if -->
				<?php if ($issue->file("logo-black.pdf")) : ?>
					<a href="<?= $issue->file("logo-black.pdf")->url() ?>">
						logo-black.pdf
					</a>
				<?php endif ?>
			</details>
		</li>
	<?php endforeach ?>
</ul>
<?php endslot() ?>

<?php slot("main") ?>

<?php endslot() ?>
