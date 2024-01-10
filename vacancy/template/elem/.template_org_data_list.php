<div id="job-data-org" class="job-data">

    <?php if ($_GET['type'] === 'vacancy'): ?>
        <?php foreach ($GLOBALS['JM']['HTML']['DATA_ORG'][$_GET['org']]['vacancy'] as $id => $item): ?>
            <div class="elem">
                <div class="section-name"><?= $GLOBALS['JM']['LANG']['POSITION'] ?></div>
                <div class="section"><?= $item['Name' . $GLOBALS['JM']['LOCAL_Xx']] ?></div>
                <div class="section-name"><?= $GLOBALS['JM']['LANG']['DEPARTMENT'] ?> / <?= $GLOBALS['JM']['LANG']['ORG'] ?></div>
                <div class="section"><?= $item['Department' . $GLOBALS['JM']['LOCAL_Xx']] ?><br /><?= $GLOBALS['JM']['HTML']['TITLE'] ?></div>
                <div class="section-name"><?= $GLOBALS['JM']['LANG']['REQUIREMENTS'] ?></div>
                <div class="section"><?= $item['Requirements' . $GLOBALS['JM']['LOCAL_Xx']] ?></div>
                <div class="section-name"><?= $GLOBALS['JM']['LANG']['DESCRIPTION'] ?></div>
                <div class="section"><?= $item['Description' . $GLOBALS['JM']['LOCAL_Xx']] ?></div>
                <a class="big" href="<?= JM_create_link(['url' => '/vacancy/org_data.php', 'form' => 'yes', 'id' => $id]); ?>">
                    <?= $GLOBALS['JM']['LANG']['RESPOND_VACANCY'] ?>
                </a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if ($_GET['type'] === 'contest'): ?>
        <?php foreach ($GLOBALS['JM']['HTML']['DATA_ORG'][$_GET['org']]['contest'] as $id => $item): ?>
            <div class="elem">
                <div class="section-name"><?= $GLOBALS['JM']['LANG']['POSITION'] ?></div>
                <div class="section"><?= $item['Name' . $GLOBALS['JM']['LOCAL_Xx']] ?></div>
                <div class="section-name"><?= $GLOBALS['JM']['LANG']['DEPARTMENT'] ?> / <?= $GLOBALS['JM']['LANG']['ORG'] ?></div>
                <div class="section"><?= $item['Department' . $GLOBALS['JM']['LOCAL_Xx']] ?><br /><?= $GLOBALS['JM']['HTML']['TITLE'] ?></div>
                <div class="section-name"><?= $GLOBALS['JM']['LANG']['REQUIREMENTS'] ?></div>
                <div class="section"><?= $item['Requirements' . $GLOBALS['JM']['LOCAL_Xx']] ?></div>
                <div class="section-name"><?= $GLOBALS['JM']['LANG']['DESCRIPTION'] ?></div>
                <div class="section"><?= $item['Description' . $GLOBALS['JM']['LOCAL_Xx']] ?></div>
                <a class="big" href="<?= JM_create_link(['url' => '/vacancy/org_data.php', 'form' => 'yes', 'id' => $id]); ?>">
                    <?= $GLOBALS['JM']['LANG']['RESPOND_CONTEST'] ?>
                </a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

</div>