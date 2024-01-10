<div id="job-list-org" class="job-data">

    <?php foreach ($GLOBALS['JM']['HTML']['DATA_ORG'] as $gid => $org): ?>
<?php if ($org['vacancy_count']>0){?>
        <a href="<?= JM_create_link(['url' => '/vacancy/org_data.php', 'type' => vacancy, 'org' => $gid, 'lang' => $_GET['lang']]) ?>">
            <?= $org['short_name'] ?>
            <br />
            <span class="long"><?= $org['long_name'] ?></span>
            <br />
            <span class="count-<?= $org['vacancy_count'] ?>">
                <?= $GLOBALS['JM']['LANG']['VACANCY_TAB'] ?>: <b><?php if ($org['vacancy_count']>0){echo $org['vacancy_count'];} ?></b>
            </span>
           
        </a>
<?php }?>
    <?php endforeach; ?>

</div>