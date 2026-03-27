<?php
/** @var \Kirby\Cms\Block $block */
if ($audio = $block->audio()->toFile()):
?>
<figure class="audio-block">
  <audio controls preload="metadata">
    <source src="<?= $audio->url() ?>" type="<?= $audio->mime() ?>">
  </audio>
  <?php if ($block->caption()->isNotEmpty()): ?>
  <figcaption><?= $block->caption() ?></figcaption>
  <?php endif ?>
</figure>
<?php endif ?>
