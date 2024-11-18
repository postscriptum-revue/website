<?php snippet("site/layout", slots: true) ?>

<?php slot("aside_button") ?>Actualités<?php endslot() ?>

<?php slot("aside") ?>
<?php snippet("home/news") ?>
<?php endslot() ?>

<?php slot("main") ?>
<?php snippet("home/future-news") ?>
<?php snippet("home/recent-posts") ?>
<?php snippet("home/last-issue") ?>
<?php snippet("home/latest-issues") ?>
<?php endslot() ?>
