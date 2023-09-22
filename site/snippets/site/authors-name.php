<?php
    $names = [];
    foreach ($page->authors()->toStructure() as $aut)
            array_push($names, $aut->name());
    echo implode(', ', $names);
?>