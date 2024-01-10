<!DOCTYPE html>
<html>
    <head>
        <title><?= $GLOBALS['JM']['HTML']['TITLE'] ?></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, height=device-height, viewport-fit=cover">
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
        <script src="template/elem/jquery.js"></script>
        <script src="template/elem/script.js?v1"></script>
        <link href="template/elem/style.css?v1" type="text/css" media="all" rel="stylesheet" />
        <script src='https://www.google.com/recaptcha/api.js?hl=<?= $GLOBALS['JM']['LOCAL_xx'] ?>'></script>
    </head>
    <body>
        <div id="loader"></div>
        <div class="job-module">

           <div class="job-menu">
		<a href="#" onclick="history.back();return false;"> <?= $GLOBALS['JM']['LANG']['BACK']?> </a>

                <table class="job-toolbar">
                    <tr>

                        <?php if (!empty($GLOBALS['JM']['HTML']['BACK'])): ?>
                            <td>
                                <a href="<?= $GLOBALS['JM']['HTML']['BACK'] ?>">
                                    <?= $GLOBALS['JM']['HTML']['BACK_TITLE'] ?>
                                </a>                            
                            </td>
                        <?php endif; ?>

                            <td class="w100">
                                <span><?= $GLOBALS['JM']['HTML']['TITLE'] ?></span>
                            </td>
                            
                        <?php if (!empty($GLOBALS['JM']['HTML']['EXCEL'])): ?>
                            <td>
                                <a class="file" href="<?= $GLOBALS['JM']['HTML']['EXCEL'] ?>">XLS</a>                            
                            </td>
                        <?php endif; ?>

                    <?php if ($GLOBALS['JM']['LOCAL_TOOL']): ?>
                        <?php foreach ($GLOBALS['JM']['LOCAL'] as $lang => $e): ?>
                            <?php if ($lang === $_GET['lang']): ?>
                                <td><a class="active"><?= $lang ?></a></td>
                            <?php else: ?>
                                <td><a href="<?= JM_create_link(['url' => '', 'lang' => $lang]) ?>"><?= $lang ?></a></td>
                            <?php endif; ?>
                        <?php endforeach; ?>
                <?php endif; ?> 
                            
                    </tr>
                </table>
               
            </div>

            <?php include_once 'elem' . DIRECTORY_SEPARATOR . $GLOBALS['JM']['HTML']['TEMPLATE'] ?>

            <?php if (isset($GLOBALS['JM']['HTML']['MESSAGE'])): ?>
                <table class="job-toolbar">
                    <tr>
                    <td class="w100"></td>
                    <td class="nowrap">
                        <span class="message">
                            
                            <?= implode(' / ', $GLOBALS['JM']['HTML']['MESSAGE']) ?>
                        
                        </span>
                    </td>
                    </tr>
                </table>
            <?php endif; ?>

        </div>
    </body>
</html>