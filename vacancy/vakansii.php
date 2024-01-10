<?php

# параметры
    include_once '.conf.php';

# подключение файла локализации
    include_once 'lang' . DIRECTORY_SEPARATOR . $GLOBALS['JM']['LOCAL'][$_GET['lang']];

# подключение библиотеки функций
    include_once '.func.php';

# получение данных
    $data = JM_get_data_org();
    $GLOBALS['JM']['HTML']['DATA_ORG'] = $data['data'];
    $GLOBALS['JM']['HTML']['MESSAGE'] = $data['message'];

# вывод
    $GLOBALS['JM']['HTML']['BACK'] = true;
    $GLOBALS['JM']['HTML']['BACK_TITLE'] = 'назад';
   $GLOBALS['JM']['HTML']['TITLE'] = $GLOBALS['JM']['LANG']['LIST_ORG'] . ' - ' . count($GLOBALS['JM']['HTML']['DATA_ORG']);
    $GLOBALS['JM']['HTML']['TEMPLATE'] = '.template_org_list.php';
    include_once 'template' . DIRECTORY_SEPARATOR . '.template.php';
