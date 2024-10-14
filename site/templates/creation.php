<?php snippet("site/layout", slots: true) ?>

<?php slot("aside_button") ?>Détails<?php endslot() ?>

<?php slot("aside") ?>
<?php snippet("article/aside") ?>
<?php endslot() ?>

<?php slot("main") ?>
<?php snippet("article/text-section") ?>
<?php endslot() ?>
