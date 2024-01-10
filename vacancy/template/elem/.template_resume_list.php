<div id="job-list-org" class="job-data">

    <?php foreach ($GLOBALS['JM']['HTML']['DATA_RESUME'] as $id => $resume): ?>
        <a href="<?= JM_create_link(['url' => '/vacancy/resume_data.php', 'id' => $id, 'lang' => $_GET['lang']]) ?>">
            <?= $resume['name'] ?>
            <br />
            <span class="long"><?= $GLOBALS['JM']['HTML']['DATA_ORG'][$resume['org']]['long_name'] ?></span>
            <br />
            <span class="count-1">
                <b><?= $resume['time'] ?></b>
                    <?php 
                        if ($resume['data']['VacancyId'] === '') echo '&emsp;/&emsp;' . $GLOBALS['JM']['LANG']['WISH_TAB'];
                        else
                            {
                            if ($resume['data']['IsContest'] !== '') echo '&emsp;/&emsp;' . $GLOBALS['JM']['LANG']['CONTEST'];
                            else echo '&emsp;/&emsp;' . $GLOBALS['JM']['LANG']['VACANCY'];
                            }
                    ?>
            </span>
        </a>
    <?php endforeach; ?>

</div>