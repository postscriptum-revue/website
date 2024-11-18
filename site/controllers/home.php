<?php

use Kirby\Cms\Pages;

return function () {

	$news_page = page("actualites");
	$all_news = new Pages();

	foreach ($news_page->children() as $news_section) { 
		$all_news->add($news_section->children()->listed());
	}

	$future_news = new Pages();
	$today = strtotime('today');

	foreach($all_news as $news){
		if ($news->date_end()->toDate() >= $today) {
			$future_news->add($news);
		}
	}

	$last_issue = page("numeros")->children()->listed()->last();
	$latest_issues = page("numeros")
		->children()->listed()->flip()->slice(1, 3);

	$recentPosts = pages()->children();
	$timeAgo = strtotime('-3 months');

	$comptesrendus = page('comptes-rendus')->children()->listed()->sortBy('date', 'desc');
	$comptesrendus = $comptesrendus->filter(function ($comptesrendu) use ($timeAgo) {
		return $comptesrendu->date()->toDate() >= strtotime('-3 months');
	});
	$recentPosts = $recentPosts->merge($comptesrendus);

	$entretiens = page('entretiens')->children()->listed()->sortBy('date', 'desc');
	$entretiens = $entretiens->filter(function ($entretien) use ($timeAgo) {
		return $entretien->date()->toDate() >= $timeAgo;
		$strtotime('-3 months');
	});
	$recentPosts = $recentPosts->merge($entretiens);

	$creations = page('creations')->children()->listed()->sortBy('date', 'desc');
	$creations = $creations->filter(function ($creation) use ($timeAgo) {
		return $creation->date()->toDate() >= $timeAgo;
	});
	$recentPosts = $recentPosts->merge($creations);

	$recentPosts = $recentPosts->sortBy('date', 'desc');

	$recentPosts = pages()->children();
	$timeAgo = strtotime('-3 months');

	$comptesrendus = page('comptes-rendus')->children()->listed()->sortBy('date', 'desc');
	$comptesrendus = $comptesrendus->filter(function ($comptesrendu) use ($timeAgo) {
		return $comptesrendu->date()->toDate() >= strtotime('-3 months');
	});
	$recentPosts = $recentPosts->merge($comptesrendus);

	$entretiens = page('entretiens')->children()->listed()->sortBy('date', 'desc');
	$entretiens = $entretiens->filter(function ($entretien) use ($timeAgo) {
		return $entretien->date()->toDate() >= $timeAgo;
		$strtotime('-3 months');
	});
	$recentPosts = $recentPosts->merge($entretiens);

	$creations = page('creations')->children()->listed()->sortBy('date', 'desc');
	$creations = $creations->filter(function ($creation) use ($timeAgo) {
		return $creation->date()->toDate() >= $timeAgo;
	});
	$recentPosts = $recentPosts->merge($creations);

	$recentPosts = $recentPosts->sortBy('date', 'desc');

	return [
		"last_issue" => $last_issue,
		"latest_issues" => $latest_issues,
		"all_news" => $all_news->flip(),
		"recentPosts" => $recentPosts,
		"future_news" => $future_news->flip(),
	];
};