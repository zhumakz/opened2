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

# проверка входящего параметра (org)
    foreach ($GLOBALS['JM']['HTML']['DATA_ORG'] as $gid => $org)
        {
        if (!empty($_GET['org']) && $_GET['org'] == $gid)
            {
            $GLOBALS['JM']['HTML']['TITLE'] = $org['short_name'];
            break;
            }
        }
    if (empty($GLOBALS['JM']['HTML']['TITLE']))
        {
        include_once 'org_list.php';
        exit;
        }

# проверка входящего параметра (type)
    $GLOBALS['JM']['HTML']['TYPE'] = [
        'wish' => $GLOBALS['JM']['LANG']['WISH_TAB'],
        'vacancy' => $GLOBALS['JM']['LANG']['VACANCY_TAB'] . ' - ' . $GLOBALS['JM']['HTML']['DATA_ORG'][$_GET['org']]['vacancy_count'], 
        'contest' => $GLOBALS['JM']['LANG']['CONTEST_TAB'] . ' - ' . $GLOBALS['JM']['HTML']['DATA_ORG'][$_GET['org']]['contest_count']
    ];
    if ($GLOBALS['JM']['HTML']['DATA_ORG'][$_GET['org']]['vacancy_count'] === 0) unset($GLOBALS['JM']['HTML']['TYPE']['vacancy']);
    if ($GLOBALS['JM']['HTML']['DATA_ORG'][$_GET['org']]['contest_count'] === 0) unset($GLOBALS['JM']['HTML']['TYPE']['contest']);
    if (!isset($_GET['type']) || !isset($GLOBALS['JM']['HTML']['TYPE'][$_GET['type']])) $_GET['type'] = 'wish';
    if ($_GET['type'] === 'wish') $_GET['form'] = 'yes';

# определение шаблона
    $GLOBALS['JM']['HTML']['TEMPLATE'] = '.template_org_data.php';
    if ($_GET['form'] !== 'yes') $GLOBALS['JM']['HTML']['TEMPLATE_PAGE'] = '.template_org_data_list.php';
    else 
        {
        if (isset($_POST['FORM_RESUME']))
            {
            JM_set_data_resume();
            $GLOBALS['JM']['HTML']['TEMPLATE'] = '.template_resume_add.php';
            }
        else {
            $GLOBALS['JM']['HTML']['TEMPLATE_PAGE'] = '.template_org_data_form.php';
            $GLOBALS['JM']['HTML']['LIST_SUBJECT'] = JM_get_data_list('subject', 'ShortName');
            $GLOBALS['JM']['HTML']['LIST_EDUCATION_LVL'] = JM_get_data_list('education-lvl');
            $GLOBALS['JM']['HTML']['LIST_EDUCATION_ORG'] = JM_get_data_list('education-org');
            $GLOBALS['JM']['HTML']['LIST_COUNTRY'] = JM_get_data_list('country');
            $GLOBALS['JM']['HTML']['LIST_CITY'] = JM_get_data_city();
            }
        }

# вывод
    $GLOBALS['JM']['HTML']['BACK'] = JM_create_link(['url' => 'career.nis.edu.kz', 'org' => false, 'type' => false, 'form' => false]);
    $GLOBALS['JM']['HTML']['BACK_TITLE'] = $GLOBALS['JM']['LANG']['LIST'];
    include_once 'template' . DIRECTORY_SEPARATOR . '.template.php';