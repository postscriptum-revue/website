<button onclick="copyToClipboard()">Citer</button>

<div class="reference__modal">
<?php foreach ($page->authors()->toStructure() as $author) : ?>
    <?= $author->name() ?>
<?php endforeach ?>
."
<?= $page->title() ?> <?= $page->subtitle() ?>."
post-scriptum.org,
<?= $page->parent()->date() ?>,
<?= $page->url() ?>.
</div>