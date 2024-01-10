<div id="job-list-org" class="job-data">

    <?php foreach ($GLOBALS['JM']['HTML']['DATA_ORG'] as $gid => $org): ?>
    
<a href="<?= JM_create_link(['url' => '/vacancy/org_data.php', 'type' => contest, 'org' => $gid, 'lang' => $_GET['lang']]) ?>">
            <?= $org['short_name'] ?>
            <br />
            <span class="long"><?= $org['long_name'] ?></span>
            <br />
      
            <span class="count-<?= $org['contest_count']?>">
                <?= $GLOBALS['JM']['LANG']['CONTEST_TAB'] ?>: <b><?= $org['contest_count'] ?></b>
            </span>

        </a>
    <?php endforeach; ?>

</div>