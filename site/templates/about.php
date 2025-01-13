<?php snippet("site/layout", slots: true) ?>

<?php slot("aside") ?>

<section class="site-aside__section">
    <h2 class="site-aside__title">Coordonnées</h2>
    <ul class="site-aside__list">
        <?php
        foreach ($page->contact()->toStructure() as $contact) :
        ?>
            <li class="site-aside__list-item">
                <a href="<?= $contact->address() ?>">
                    <?= $contact->address() ?>
                </a>
            </li>
        <?php endforeach ?>
    </ul>
</section>
<section class="site-aside__section">
    <h2 class="site-aside__title">Équipe</h2>
    <ul class="site-aside__list">
        <?php
        foreach ($page->team()->toStructure() as $group) :
        ?>
            <li class="site-aside__list-item about-aside-group">
                <h3 class="site-aside__subtitle">
                    <?= $group->task() ?>
                </h3>
                <ul>
                    <?php
                    foreach ($group->members()->toStructure()
                        as $member) :
                    ?>
                        <li>
                            <?php snippet(
                                "about/member",
                                ["member" => $member]
                            ) ?>
                        </li>
                    <?php endforeach ?>
                </ul>
            </li>
        <?php endforeach ?>
    </ul>
</section>

<?php endslot() ?>

<?php slot("main") ?>
<section class="text-section">
    <?= $page->about()->toBlocks() ?>
</section>

<?php endslot() ?>