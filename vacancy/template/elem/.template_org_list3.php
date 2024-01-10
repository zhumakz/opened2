<div id="job-list-org" class="job-data">

    <?php foreach ($GLOBALS['JM']['HTML']['DATA_ORG'] as $gid => $org): ?>
    
<a href="<?= JM_create_link(['url' => '/vacancy/org_data.php', 'org' => $gid, 'lang' => $_GET['lang']]) ?>">
            <?= $org['short_name'] ?>
            <br />
            <span class="long"><?= $org['long_name'] ?></span>
            <br />
  

        </a>
    <?php endforeach; ?>

</div>