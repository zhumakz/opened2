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
    if (!isset($_GET['id']) || !isset($GLOBALS['JM']['HTML']['DATA_RESUME'][$_GET['id']]))
        {
        require_once 'resume_list.php';
        exit;
        }
    else $GLOBALS['JM']['HTML']['DATA_RESUME'] = $GLOBALS['JM']['HTML']['DATA_RESUME'][$_GET['id']];
    if (isset($_GET['id']))
        {
        $GLOBALS['JM']['HTML']['LIST_SUBJECT'] = JM_get_data_list('subject', 'ShortName');
        $GLOBALS['JM']['HTML']['LIST_EDUCATION_LVL'] = JM_get_data_list('education-lvl');
        $GLOBALS['JM']['HTML']['LIST_EDUCATION_ORG'] = JM_get_data_list('education-org');
        $GLOBALS['JM']['HTML']['LIST_COUNTRY'] = JM_get_data_list('country');
        $GLOBALS['JM']['HTML']['LIST_CITY'] = JM_get_data_city();

        if ($GLOBALS['JM']['HTML']['DATA_RESUME']['data']['VacancyId'] !== '')
            {
            if ($GLOBALS['JM']['HTML']['DATA_RESUME']['data']['IsContest']) $type = 'contest';
            else $type = 'vacancy';
            $GLOBALS['JM']['HTML']['LIST_JOB'] = JM_get_data_job('all');
            $Gid_Bin = JM_set_gid_to_bin('Gid_Bin');
            $bin = $Gid_Bin[$GLOBALS['JM']['HTML']['DATA_RESUME']['data']['OrganizationBin']];
            foreach ($GLOBALS['JM']['HTML']['LIST_JOB'][$bin][$type] as $id => $item)
                {
                $GLOBALS['JM']['HTML']['POSITION_JOB'] = $item['Name' . $GLOBALS['JM']['LOCAL_Xx']];
                $GLOBALS['JM']['HTML']['DEPARTAMENT_JOB'] = $item['Department' . $GLOBALS['JM']['LOCAL_Xx']];
                }
            }
        }
    
# вывод
    $GLOBALS['JM']['HTML']['BACK'] = JM_create_link(['url' => '/vacancy/resume_list.php', 'id' => false]);
    $GLOBALS['JM']['HTML']['EXCEL'] = JM_create_link(['url' => '/vacancy/resume_list.php', 'xls' => 'ok']);
    $GLOBALS['JM']['HTML']['BACK_TITLE'] = $GLOBALS['JM']['LANG']['LIST'];
    $GLOBALS['JM']['HTML']['TITLE'] = $GLOBALS['JM']['HTML']['DATA_RESUME']['name'];
    $GLOBALS['JM']['HTML']['TEMPLATE'] = '.template_resume_data.php';
    include_once 'template' . DIRECTORY_SEPARATOR . '.template.php';