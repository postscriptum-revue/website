<?php snippet("site/layout", slots: true) ?>

<?php slot("aside_button") ?>Publications récentes<?php endslot() ?>

<?php slot("aside") ?>
<?php snippet("home/recent-posts_aside") ?>
<?php endslot() ?>

<?php slot("main") ?>
<?php snippet("home/future-news") ?>
<?php if ($isIssueMoreRecent):
	snippet("home/last-issue");
	snippet("home/recent-posts");
else:
	snippet("home/recent-posts");
	snippet("home/last-issue");
endif; ?>
<?php snippet("home/latest-issues") ?>
<?php endslot() ?>