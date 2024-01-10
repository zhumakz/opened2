<?php

# параметры
    include_once '.conf.php';

# проверка доступа
    include_once '.access.php';

# подключение файла локализации
    include_once 'lang' . DIRECTORY_SEPARATOR . $GLOBALS['JM']['LOCAL'][$_GET['lang']];

# подключение библиотеки функций
    include_once '.func.php';

# получение данных
    $data = JM_get_data_org();
    $GLOBALS['JM']['HTML']['DATA_ORG'] = $data['data'];
    $GLOBALS['JM']['HTML']['MESSAGE'] = $data['message'];
    $GLOBALS['JM']['HTML']['DATA_RESUME'] = JM_get_data_resume();

# вывод EXCEL
    if (!empty($_GET['xls'])) JM_excel_get();

# вывод
    $GLOBALS['JM']['HTML']['BACK'] = false;
    $GLOBALS['JM']['HTML']['BACK_TITLE'] = '';
    $GLOBALS['JM']['HTML']['EXCEL'] = JM_create_link(['url' => '/vacancy/resume_list.php', 'xls' => 1]);
    $GLOBALS['JM']['HTML']['TITLE'] = $GLOBALS['JM']['LANG']['LIST_RESUME'];
    $GLOBALS['JM']['HTML']['TEMPLATE'] = '.template_resume_list.php';
    include_once 'template' . DIRECTORY_SEPARATOR . '.template.php';