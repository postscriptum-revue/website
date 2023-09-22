<?=
    snippet("site/authors-name") . ". " .
    $page->title() . " : " . $page->subtitle() . ". " .
    "Post-Scriptum" . ". " .
    $page->parent()->date() . ". " .
    $page->url()
?>