<div class="job-menu">

    <table class="job-head-menu">
        <tr>

        <?php foreach ($GLOBALS['JM']['HTML']['TYPE'] as $type => $title): ?>
            <?php if ($type == $_GET['type']): ?>
                <td class="nowrap"><a class="active"><?= $title ?></a></td>            
            <?php else: ?>
                <td class="nowrap"><a href="<?= JM_create_link(['url' => '', 'type' => $type, 'form' => false]) ?>"><?= $title ?></a></td>
            <?php endif; ?>
        <?php endforeach; ?>
                
            <td class="w100"></td>
        </tr>
    </table>

</div>

<?php include_once $GLOBALS['JM']['HTML']['TEMPLATE_PAGE'] ?>