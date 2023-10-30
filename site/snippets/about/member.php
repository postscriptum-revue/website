<?php

$name = $member->name();
$occupation = $member->occupation()->lower();
$affiliation = $member->affiliation();

echo $member->name();

if ($occupation != "" && $affiliation != "") {
	echo " ($occupation, $affiliation)";
} elseif ($affiliation != "") {
	echo " ($affiliation)";
}
