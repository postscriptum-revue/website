<?php
/** @var \Kirby\Cms\Block $block */
$table   = $block->table()->toTable();
$caption = $block->caption();
$hasHeaders = !empty(array_filter($table['headers'] ?? [], fn($h) => trim(strip_tags($h)) !== ''));
?>
<?php if($table != null): ?>
<table>
  <?php if ($caption->isNotEmpty()): ?>
  <caption><?= $caption ?></caption>
  <?php endif ?>
  <?php if ($hasHeaders): ?>
  <thead>
    <tr>
      <?php foreach ($table['headers'] as $header): ?>
        <th><?= $header ?></th>
      <?php endforeach ?>
    </tr>
  </thead>
  <?php endif ?>
  <tbody>
    <?php foreach ($table['rows'] as $row): ?>
      <tr>
        <?php foreach ($row as $cell): ?>
          <td><?= $cell ?></td>
        <?php endforeach ?>
      </tr>
    <?php endforeach ?>
  </tbody>
</table>
<?php endif ?>
