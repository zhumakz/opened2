<?php

# ПОДКЛЮЧЕНИЕ К БД ################################################################################################################
    function JM_connect_db()
        {
        $GLOBALS['JM']['DB_LINK'] -> real_connect(
            $GLOBALS['JM']['DB_HOST'],
            $GLOBALS['JM']['DB_USER'],
            $GLOBALS['JM']['DB_PASS'],
            $GLOBALS['JM']['DB_NAME'],
            $GLOBALS['JM']['DB_PORT']
        );
        $GLOBALS['JM']['DB_LINK'] -> set_charset('utf8');
        }

# СОХРАНЕНИЕ В БД ПОЛУЧЕННЫХ ДАННЫХ ПО ВАКАНСИЯМ И КОНКУРСАМ ######################################################################
    function JM_set_data_job($data)
        {
        # проверка входящих данных
        if (count($data) === 0) return false;
        # подключение к БД
            JM_connect_db();
            $table = $GLOBALS['JM']['DB_PREFIX'] . 'job';
        # запрос id существующих вакансий
            $arrID = [];
            $res = $GLOBALS['JM']['DB_LINK'] -> query("SELECT `id_1C` FROM `" . $table . "`");
            while ($row = $res -> fetch_assoc()) $arrID[$row['id_1C']] = $row['id_1C'];
        # поиск дубликатов
            $double = [];
            foreach ($data as $e)
                {
                if ($e['Id'] === '') continue;
                if (isset($double[$e['Id']])) $double[$e['Id']]++;
                else $double[$e['Id']] = 1;
                }
        # подготовка данных
            $add = [];
            $edit = [];
            foreach ($data as $e)
                {
                if ($double[$e['Id']] > 1 && $e['Id'] !== '') continue;
                $c = ['','','','','',''];
                $c[0] = $GLOBALS['JM']['DB_LINK'] -> real_escape_string($e['Id']);
                $c[1] = $GLOBALS['JM']['DB_LINK'] -> real_escape_string($e['OrganizationBin']);
                $c[3] = $GLOBALS['JM']['DB_LINK'] -> real_escape_string($e['Date']);
                $c[4] = $GLOBALS['JM']['DB_LINK'] -> real_escape_string($e['EndDate']);
                $c[5] = json_encode($e, JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE);
                $c[5] = $GLOBALS['JM']['DB_LINK'] -> real_escape_string($c[5]);
                if ($e['IsContest']) $c[2] = 'contest';
                else $c[2] = 'vacancy';
                if (isset($arrID[$e['Id']])) $edit[$e['Id']] = $c;
                else $add[] = "('" . implode("','", $c) . "')";
                }
        # сохранение данных в БД
            $sql = "INSERT INTO `" . $table . "` (`id_1C`, `bin`, `type`, `start`, `end`, `data`) 
                         VALUES " . implode(',', $add) . ";";
            $GLOBALS['JM']['DB_LINK'] -> query($sql);
        # обновление данных в БД
            foreach ($edit as $id => $e)
                {
                $sql = "UPDATE `" . $table . "` 
                           SET `bin` = '" . $e[1] . "',
                               `type` = '" . $e[2] . "',  
                               `start` = '" . $e[3] . "',
                               `end` = '" . $e[4] . "',
                               `data` = '" . $e[5] . "',
                               `added` = NOW() 
                         WHERE `id_1C` = '" . $id . "' ;";
                $GLOBALS['JM']['DB_LINK'] -> query($sql);                
                }
        }

# СОХРАНЕНИЕ РЕЗЮМЕ ###############################################################################################################
    function JM_set_data_resume()
        {
        # проверка наличия данных из формы
            if (!isset($_POST['FORM_RESUME'])) return false; 
        # проверка капчи
            if ($GLOBALS['JM']['CAPCHA_CHECK'])
                {
                if (!empty($_POST['g-recaptcha-response']) && $_POST['g-recaptcha-response'] !== '')
                    {
                    $param = 'secret=' . $GLOBALS['JM']['CAPCHA_BACKEND'] . '&response=' . $_POST['g-recaptcha-response'];
                    $res = file_get_contents('https://www.google.com/recaptcha/api/siteverify?' . $param);
                    $captcha = false;
                    $captcha = json_decode($res, JSON_OBJECT_AS_ARRAY);
                    if (!$captcha || !$captcha['success']) return false;
                    }
                else return false;
                }
        # форматирование данных
            unset($_POST['g-recaptcha-response'], $_POST['FORM_RESUME']);
            foreach ($_POST as $key => $value)
                {
                if (is_array($value))
                    {
                    foreach ($value as $i => $e)
                        {
                        if (is_array($e)) return false;
                        if ($key === 'Note') $_POST[$key][$i] = substr(trim($e), 0, 5000);
                        else $_POST[$key][$i] = substr(trim($e), 0, 1000);
                        }
                    }
                else $_POST[$key] = substr(trim($value), 0, 1000);
                }
        # проверка данных резюме
            if ($_GET['type'] === 'contest')
                {
                if (JM_check_data_post('TestingLanguage')) return false;
                if (!in_array($_POST['TestingLanguage'], [1,2,3])) return false;
                if (JM_check_data_post('Subject')) return false;
                }

            # if ($_GET['type'] === 'wish')
            #     {
            #     if (JM_check_data_post('Temp_2')) return false;
            #     if (JM_check_data_post('Temp_3')) return false;
            #     }

            if (JM_check_data_post('FirstName')) return false;
            if (JM_check_data_post('SecondName')) return false;
            if (JM_check_data_post('BirthDate')) return false;
            if (JM_check_date('BirthDate')) return false;
            if (JM_check_data_post('Iin')) return false;

            if (JM_check_data_post('KazakhLevel')) return false;
            if (JM_check_data_post('RussianLevel')) return false;
            if (JM_check_data_post('EnglishLevel')) return false;

            $arr = [
                'EducationLevel', 'StartDate', 'FinishDate', 'EducationOrganization', 
                'Country', 'City', 'IsBolashak', 'Specialty', 'DiplomaNumber', 'WorkTheme'
            ];
            if (JM_check_data_post_arr($arr)) return false;

            $arr = ['StartDate', 'FinishDate'];
            if (JM_check_date_arr($arr)) return false;

            if (JM_check_data_post('TotalExperience')) return false;
            if (JM_check_data_post('EducationExperience')) return false;
            if (JM_check_data_post('Phone')) return false;
            if (JM_check_data_post('Email')) return false;
            if (JM_check_data_post('ActualCity')) return false;
        # подготовка и проверка файлов
            $files = [];
            $files['SummaryFiles'] = [];
            
           

            
            $mime_type = [
                'image/gif',
                'image/jpeg',
                'image/png',
                'image/tiff', 
                'application/pdf', 
                'application/msword', 
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 
                'application/vnd.ms-excel', 
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            ];

            if ($_GET['type'] === 'contest') 
                {
                
                }
            if (!file_exists($_FILES['SummaryFiles']['tmp_name'][0])) return false;
            $max = 1;
            foreach ($_FILES['SummaryFiles']['name'] as $k => $e)
                {
                if ($max > 10 || $_FILES['SummaryFiles']['tmp_name'][$k] === '') break;
                if (!in_array(mime_content_type($_FILES['SummaryFiles']['tmp_name'][$k]), $mime_type)) return false; # !!!
                $files['SummaryFiles'][] = JM_save_file_post($e, $_FILES['SummaryFiles']['tmp_name'][$k]);
                $max++;
                }
        # подготовка данных для сохранения
            $data['OrganizationBin'] = $_GET['org'];
            $data['IsContest'] = false;

            if ($_GET['type'] === 'wish') $data['VacancyId'] = '';
            else
                {
                if (!isset($GLOBALS['JM']['HTML']['DATA_ORG'][$_GET['org']][$_GET['type']][$_GET['id']])) return false;
                if ($_GET['type'] === 'contest') $data['IsContest'] = true;
                $data['VacancyId'] = $GLOBALS['JM']['HTML']['DATA_ORG'][$_GET['org']][$_GET['type']][$_GET['id']]['Id'];
                }

            # $data['Temp_2'] = $_POST['Temp_2'];
            # $data['Temp_3'] = $_POST['Temp_3'];

            $data['FirstName'] = $_POST['FirstName'];
            $data['SecondName'] = $_POST['SecondName'];
            $data['Iin'] = $_POST['Iin'];
            $data['BirthDate'] = $_POST['BirthDate'];
            $data['KazakhLevel'] = $_POST['KazakhLevel'];
            $data['RussianLevel'] = $_POST['RussianLevel'];
            $data['EnglishLevel'] = $_POST['EnglishLevel'];

            foreach ($_POST['EducationLevel'] as $i => $e)
                {
                $temp = [];
                $temp['EducationLevelId'] = $_POST['EducationLevel'][$i];
                $temp['EducationOrganization'] = $_POST['EducationOrganization'][$i];
                $temp['Country'] = $_POST['Country'][$i];
                $temp['City'] = $_POST['City'][$i];
                $temp['StartDate'] = $_POST['StartDate'][$i];
                $temp['FinishDate'] = $_POST['FinishDate'][$i];
                $temp['IsBolashak'] = (boolean) $_POST['IsBolashak'][$i];
                $temp['DiplomaNumber'] = $_POST['DiplomaNumber'][$i];
                $temp['WorkTheme'] = $_POST['WorkTheme'][$i];
                $temp['Specialty'] = $_POST['Specialty'][$i];
                $temp['Note'] =  $_POST['Note'][$i];

                $temp['EducationLevelIdHand'] = $_POST['EducationLevelHand'][$i];
                $temp['EducationOrganizationHand'] = $_POST['EducationOrganizationHand'][$i];
                $temp['CityHand'] = $_POST['CityHand'][$i];

                $data['Education'][] = $temp;
                }
            
            $data['TotalExperience'] = (int) $_POST['TotalExperience'];
            $data['EducationExperience'] = (int) $_POST['EducationExperience'];
            $data['Phone'] = $_POST['Phone'];
            $data['Email'] = $_POST['Email'];
            $data['ActualCity'] = $_POST['ActualCity'];
            $data['ActualCityHand'] = $_POST['ActualCityHand'];
            $data['SummaryFiles'] = $files['SummaryFiles'];

            $data['TestingLanguage'] = $_POST['TestingLanguage'];
            $data['Subject'] = $_POST['Subject'];
            $data['SubjectHand'] = $_POST['SubjectHand'];
             
        # сохранение в БД
            JM_connect_db();
            foreach ($data as $i => $e)
                {
                if (is_array($e)) foreach ($e as $ii => $ee) 
                    {
                    if (is_array($ee)) foreach ($ee as $iii => $eee)
                        {
                        $data[$i][$ii][$iii] = $GLOBALS['JM']['DB_LINK'] -> real_escape_string($eee);
                        }
                    else $data[$i][$ii] = $GLOBALS['JM']['DB_LINK'] -> real_escape_string($ee);
                    }
                else $data[$i] = $GLOBALS['JM']['DB_LINK'] -> real_escape_string($e);
                }
            $data = json_encode($data, JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE);
            $table = $GLOBALS['JM']['DB_PREFIX'] . 'resume';
            $sql = "INSERT INTO `" . $table . "` SET `value` = '" . $data . "';";
            $GLOBALS['JM']['DB_LINK'] -> query($sql);
        # возврат результата
            return true;
        }

# ПОЛУЧЕНИЕ ДАННЫХ ВЗАИМОСВЯЗИ GID И BIN ##########################################################################################
    function JM_set_gid_to_bin($type)
        {
        # получение списка организаций из API и файла-модификатора
            if (!$GLOBALS['JM']['ORG_LIST_API']) $data['api'] = [];
            else $data['api'] = JM_data_api('organization');
            if (!$GLOBALS['JM']['ORG_LIST_USER']) $data['file'] = [];
            else $data['file'] = JM_storage('DIR/get', '.data-org-mod');
        # обработка списков организаций
            $data = array_merge($data['api'], $data['file']);
            $Bin_Gid = [];
            foreach ($data as $org)
                {
                if (isset($org['Gid']) && isset($org['Bin']) && $org['Bin'])
                    {
                    if ($type ==='Bin_Gid') $Bin_Gid[$org['Bin']] = $org['Gid'];
                    if ($type === 'Gid_Bin') $Bin_Gid[$org['Gid']] = $org['Bin'];
                    }
                }
            return $Bin_Gid;
        }

# ПОЛУЧЕНИЕ ДАННЫХ ПО ВАКАНСИЯМ И КОНКУРСАМ ИЗ БД #################################################################################
    function JM_get_data_job($type)
        {
        # переменные
            $sql = ['','','',''];
            $return = [];
            JM_connect_db();
        # сборка скрипта
            $sql[0] = "SELECT `id`, `bin`, `type`, `data`";
            $sql[1] = "FROM `" . $GLOBALS['JM']['DB_PREFIX'] . "job`";
            $sql[2] = "WHERE (`start` = '' OR UNIX_TIMESTAMP(`start`) <= UNIX_TIMESTAMP()) AND 
                             (`end` = '' OR UNIX_TIMESTAMP(`end`) > UNIX_TIMESTAMP())";
            switch ($type)
                {
                case 'stat': # общая статистика
                    {
                    $sql[0] = "SELECT `bin`, `type`, COUNT(*) AS `count`";
                    $sql[3] = "GROUP BY `bin`, `type`";
                    break;
                    }
                case 'org': # фильтрация по bin
                    {
                    $Gid_Bin = JM_set_gid_to_bin('Gid_Bin');
                    if (!empty($_GET['org']))
                        {
                        $bin = $GLOBALS['JM']['DB_LINK'] -> real_escape_string($Gid_Bin[$_GET['org']]);
                        $sql[2] = $sql[2] . " AND `bin` = '" . $bin . "' ";
                        }
                    break;
                    }
                }
        # запрос данных
            $res = $GLOBALS['JM']['DB_LINK'] -> query(implode(' ', $sql));
            while ($row = $res -> fetch_assoc()) 
                {
                if ($type !== 'stat') 
                    {
                    $row['data'] = json_decode($row['data'], JSON_OBJECT_AS_ARRAY);
                    if (isset($return[$row['bin']][$row['type'] . '_count'])) $return[$row['bin']][$row['type'] . '_count']++;
                    else $return[$row['bin']][$row['type'] . '_count'] = 1;
                    $return[$row['bin']][$row['type']][$row['id']] = $row['data'];
                    }
                else $return[$row['bin']][$row['type'] . '_count'] = $row['count'];
                }
        # возврат результата
            return $return;
        }

# ПОЛУЧЕНИЕ ДАННЫХ ПО ОРГАНИЗАЦИЯМ ################################################################################################
    function JM_get_data_org()
        {
        # переменные
            $data = [];
            $return = [];
            $arr_sort = [];
            $empty_org = [];
            $shablon = ['bin' => false, 'vacancy_count' => 0, 'vacancy' => [], 'contest_count' => 0, 'contest' => []];
        # получение списка организаций из API и файла-модификатора
            if (!$GLOBALS['JM']['ORG_LIST_API']) $data['api'] = [];
            else $data['api'] = JM_data_api('organization');
            if (!$GLOBALS['JM']['ORG_LIST_USER']) $data['file'] = [];
            else $data['file'] = JM_storage('DIR/get', '.data-org-mod');
        # обработка списков организаций
            $data = array_merge($data['api'], $data['file']);
            foreach ($data as $org)
                {
                if (isset($org['Gid']))
                    {
                    if  (empty($_GET['org']) || (!empty($_GET['org']) && $_GET['org'] === $org['Gid']))
                        {
                        $return[$org['Gid']] = $shablon;
                        $return[$org['Gid']]['bin'] = $org['Bin'];
                        $return[$org['Gid']]['long_name'] = $org['Name_' . $GLOBALS['JM']['LOCAL_xx']];
                        $return[$org['Gid']]['short_name'] = $org['ShortName_' . $GLOBALS['JM']['LOCAL_xx']];
                        $arr_sort[$org['Gid']] = $org['ShortName_' . $GLOBALS['JM']['LOCAL_xx']];
                        }
                    }
                }
        # сортировка массива организации по наименованию
            if ($GLOBALS['JM']['ORG_LIST_SORT'])
                {
                asort($arr_sort);
                $data = [];
                foreach ($arr_sort as $k => $e) $data[$k] = $return[$k];
                $return = $data;
                }
        # получение данных по организациям
            if (empty($_GET['org']))
                {
                if ($GLOBALS['JM']['ORG_ISSET']) $data = JM_get_data_job('all');
                else $data = JM_get_data_job('stat');
                }
            else $data = JM_get_data_job('org');
        # обработка данных по организациям
            $Bin_Gid = JM_set_gid_to_bin('Bin_Gid');
            foreach ($data as $org => $e)
                {
                $org = $Bin_Gid[$org];
                # проверка позиции на наличие организации
                    if ($GLOBALS['JM']['ORG_ISSET'] && empty($_GET['org']) && !isset($return[$org]))
                        {
                        if (!isset($e['vacancy'])) $e['vacancy'] = [];
                        if (!isset($e['contest'])) $e['contest'] = [];
                        $temp = array_merge($e['vacancy'], $e['contest']);
                        foreach ($temp as $job) $empty_org[] = '1C:ID = ' . $job['Id'] . ' (BIN = ' . $org . ')';
                        continue;
                        }
                # выборка с учетом локализации
                    if (isset($e['vacancy_count'])) $return[$org]['vacancy_count'] = $e['vacancy_count'];
                    if (isset($e['contest_count'])) $return[$org]['contest_count'] = $e['contest_count'];
                    if (isset($e['vacancy'])) $return[$org]['vacancy'] = $e['vacancy'];
                    if (isset($e['contest'])) $return[$org]['contest'] = $e['contest'];
                }
        # обработка сообщений
            $return['data'] = $return;
            if ($GLOBALS['JM']['ORG_ISSET']) 
                {
                $return['message'][] = 'ONF-' . count($empty_org);
                JM_storage('DIR/set', 'error/.data-in-err-onf', $empty_org);
                }
        # возврат результата
            return $return;
        }

# ПОЛУЧЕНИЕ СПИСКА ДАННЫХ #########################################################################################################
    function JM_get_data_list($type, $prefix = 'Name')
        {
        $return = [];
        $data = JM_data_api($type);
        foreach ($data as $e) $return[$e['Gid']] = $e[$prefix . '_' . $GLOBALS['JM']['LOCAL_xx']];
        asort($return);
        return $return;
        }

# ПОЛУЧЕНИЕ СПИСКА ГОРОДОВ ########################################################################################################
        function JM_get_data_city($country = false)
        {
        $return = [];
        $data = JM_data_api('city');
        foreach ($data as $e)
            {
            if (!$country || (isset($e['CountryGid']) && $country === $e['CountryGid']))
                {
                $return[$e['Gid']] = $e['Name_' . $GLOBALS['JM']['LOCAL_xx']];
                }
            }
        asort($return);
        return $return;
        }

# УПРАВЛЕНИЕ ОБРАЩЕНИЕМ К API #####################################################################################################
    function JM_data_api($type, $param = [])
        {
        # параметры запроса
            if (!isset($param['skip'])) $param['skip'] = 0;
            if (!isset($param['take'])) $param['take'] = 10000;
        # формирование параметров запроса
            switch ($type)
                {
                case 'organization':
                    {
                    $cash = [true, 'cash/organization-api'];
                    $url = 'http://ref.eios.nis.edu.kz/api/Organizations?';
                    break;
                    }
                case 'subject':
                    {
                    $cash = [true, 'cash/subject-api'];
                    $url = 'http://ref.eios.nis.edu.kz/api/Subjects?';
                    break;
                    }
                case 'education-lvl':
                    {
                    $cash = [true, 'cash/education-lvl-api'];
                    $url = 'http://ref.eios.nis.edu.kz/api/EducationLevels?';
                    break;
                    }
                case 'education-org':
                    {
                    $cash = [true, 'cash/education-org-api'];
                    $url = 'http://ref.eios.nis.edu.kz/api/EducationOrganizations?';
                    break;
                    }
                case 'country':
                    {
                    $cash = [true, 'cash/country-api'];
                    $url = 'http://ref.eios.nis.edu.kz/api/Countries?';
                    break;
                    }
                case 'city':
                    {
                    $cash = [true, 'cash/city-api'];
                    $url = 'http://ref.eios.nis.edu.kz/api/Cities?';
                    break;
                    }
                default: return false;
                }
        # проверка кэша
            if ($cash[0])
                {
                $data = JM_storage('DB/get', $cash[1]);
                $time_cash = $data['time'] + $GLOBALS['JM']['API_CASH_TIME'];
                if ($time_cash > time()) return $data['data'];
                }
        # получение данных из api
            $api = JM_quety_api($url . 'skip=' . $param['skip'] . '&take=' . $param['take']);
            if (!$api)
                {
                JM_storage('DB/set', $cash[1], $data['data']);
                return $data['data'];
                }
            while (count($api['Value']) < $api['Total'])
                {
                $param['skip'] += $param['take'];
                $temp = JM_quety_api($url . 'skip=' . $param['skip'] . '&take=' . $param['take']); 
                $api['Value'] = array_merge($api['Value'], $temp['Value']);               
                }
        # подготовка данных (удаление заблокированных, добавление недостающих локализаций)
            $api = $api['Value'];
            $arr = [];
            foreach ($api as $k => $e)
                {
                if (isset($e['IsBlocked']) && $e['IsBlocked']) continue;
                if (isset($e['ForTeacherContest']) && !$e['ForTeacherContest']) continue;
                if (!isset($e['ShortName_en']) && isset($e['ShortName_ru'])) $api[$k]['ShortName_en'] = $e['ShortName_ru'];
                if (!isset($e['Name_en']) && isset($e['ShortName_ru'])) $api[$k]['Name_en'] = $e['Name_ru'];
                $arr[$k] = $api[$k];
                }
        # обновление кэша
            if ($cash[0]) JM_storage('DB/set', $cash[1], $arr);
        # возврат результата
            return $arr;
        }

# ВЫПОЛНЕНИЕ ЗАПРОСА К API ########################################################################################################
    function JM_quety_api($url)
        {
        # параметры обращения
            $opt['http']['method'] = 'GET';
            $opt['http']['header'][] = 'Accept: application/json';
            $opt['http']['header'][] = 'Authorization: Bearer ' . $GLOBALS['token_api'];
            $opt['http']['header'] = implode("\r\n", $opt['http']['header']);
            $opt = stream_context_create($opt);
        # выполнение запроса
            $data = @file_get_contents($url, false, $opt);
        # проверка статуса ответа
            $code = explode(' ', $http_response_header[0]);
            # JM_storage('DIR/set', 'test_api_header', $http_response_header);
            # JM_storage('DIR/set', 'test_api_data', $data);
            if ($code[1] !== '200') return false;
        # проверка наличия данных
            $data = json_decode($data, true);
            if (!isset($data['Value'])) return false;
        # возврат рзультата
            return $data;
        }

# ОБРАЩЕНИЕ К ХРАНИЛИЩУ ###########################################################################################################
    function JM_storage($type, $storage, $value = false)
        {
        switch ($type)
            {
            case 'DB/set': # сохранение данных в БД
                {
                JM_connect_db();
                $value = json_encode($value, JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE);
                $value = $GLOBALS['JM']['DB_LINK'] -> real_escape_string($value);
                $storage = $GLOBALS['JM']['DB_LINK'] -> real_escape_string($storage);
                $table = $GLOBALS['JM']['DB_PREFIX'] . 'storage';
                $sql = "INSERT INTO `" . $table . "` (`name`, `value`, `time`) 
                             VALUES ('" . $storage . "', '" . $value . "', NOW()) ON DUPLICATE KEY 
                             UPDATE `value` = VALUES (`value`), `time` = NOW();";
                $res = $GLOBALS['JM']['DB_LINK'] -> query($sql);
                break;
                }
            case 'DB/get': # получение данных из БД
                {
                JM_connect_db();
                $return = ['data' => '', 'time' => ''];
                $sql = "SELECT `value`, UNIX_TIMESTAMP(`time`)
                          FROM `" . $GLOBALS['JM']['DB_PREFIX'] . "storage`
                         WHERE `name` = ?;";
                $res = $GLOBALS['JM']['DB_LINK'] -> prepare($sql);
                $res -> bind_param('s', $storage);
                $res -> execute();
                $res -> bind_result($return['data'], $return['time']);
                $res -> fetch();
                $res -> close();
                $return['data'] = json_decode($return['data'], JSON_OBJECT_AS_ARRAY);
                return $return;
                break;
                }
            case 'DIR/set': # сохранение данных в директорию
                {
                $value = json_encode($value, JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE);
                return @file_put_contents($GLOBALS['JM']['STORAGE'] . $storage, $value);
                break;
                }
            case 'DIR/get': # получение данных из директории
                {
                if (!file_exists($GLOBALS['JM']['STORAGE'] . $storage)) JM_storage('DIR/set', $storage, array());
                $value = file_get_contents($GLOBALS['JM']['STORAGE'] . $storage);
                return json_decode($value, JSON_OBJECT_AS_ARRAY);
                break;
                }
            default: return false;
            }
        }

# ДЕКОДИРОВАНИЕ ДАННЫХ ############################################################################################################
    function JM_decode($arr)
        {
        if (is_array($arr))
            {
            foreach ($arr as $k => $e) $arr[$k] = JM_decode($e);
            return $arr;
            }
        else return rawurldecode($arr);
        }

# ПРОВЕРКА СТРУКТУРЫ ВХОДЯЩИХ ДАННЫХ ##############################################################################################
    function JM_check_struct_data_in($data)
        {
        if (!isset($data['Data']) || !is_array($data['Data'])) return false;
        foreach ($data['Data'] as $item)
            {
            if (!isset($item['Id'])) return false;
            if (!isset($item['OrganizationBin'])) return false;
            if (!isset($item['IsContest'])) return false;
            if (!isset($item['NameKz'])) return false;
            if (!isset($item['NameRu'])) return false;
            if (!isset($item['NameEn'])) return false;
            if (!isset($item['Date'])) return false;
            if (!isset($item['EndDate'])) return false;
            if (!isset($item['RequirementsKz'])) return false;
            if (!isset($item['RequirementsRu'])) return false;
            if (!isset($item['RequirementsEn'])) return false;
            if (!isset($item['DescriptionKz'])) return false;
            if (!isset($item['DescriptionRu'])) return false;
            if (!isset($item['DescriptionEn'])) return false;
            if (!isset($item['DepartmentKz'])) return false;
            if (!isset($item['DepartmentRu'])) return false;
            if (!isset($item['DepartmentEn'])) return false;
            }
        return true;
        }

# ГЕНЕРАЦИЯ ССЫЛОК ################################################################################################################
    function JM_create_link($in)
        {
        $arr = [];
        $arrGet = ['lang', 'org', 'type', 'form', 'id', 'token', 'xls'];
        foreach ($arrGet as $P) 
            {
            if (isset($in[$P]))
                {
                if (!$in[$P]) continue;
                else $arr[] = $P . '=' . $in[$P];
                }
            else if (isset($_GET[$P])) $arr[] = $P . '=' . $_GET[$P];
            }
        return $in['url'] . '?' . implode('&', $arr);
        }

# ОБРАБОТКА POST ДАННЫХ ###########################################################################################################
    function JM_check_data_post($name)
        {
        unset($_POST['Checkbox' . $name . 'Hand']);
        if (isset($_POST[$name . 'Hand']) && $_POST[$name] === '') $name = $name . 'Hand';
        if (!isset($_POST[$name]) || $_POST[$name] === '') return true;
        else return false;
        }
    function JM_check_data_post_arr($arr)
        {
        $return = false;
        $count = count($_POST[$arr[0]]);
        foreach ($arr as $name)
            {
            for ($i = 0; $i < $count; $i++)
                {
                $temp = $name;
                unset($_POST['Checkbox' . $name . 'Hand'][$i]);
                if (isset($_POST[$name . 'Hand'][$i]) && $_POST[$name][$i] === '') $temp = $name . 'Hand';
                if (!isset($_POST[$temp][$i]) || $_POST[$temp][$i] === '') return true;
                }
            }
        return $return;
        }
    function JM_check_date($name)
        {
        $date = explode('-', $_POST[$name]);
        return !checkdate($date[1], $date[2], $date[0]);
        }
    function JM_check_date_arr($arr)
        {
        $return = false;
        $count = count($_POST[$arr[0]]);
        foreach ($arr as $name)
            {
            for ($i = 0; $i < $count; $i++)
                {
                $date = explode('-', $_POST[$name][$i]);
                $return = !checkdate($date[1], $date[2], $date[0]);
                }
            }
        return $return;
        }
    function JM_save_file_post($name, $tmp)
        {
        $path = $GLOBALS['JM']['STORAGE'] . 'files' . DIRECTORY_SEPARATOR;
        $end = explode('.', $name);
        $end = $end[count($end) - 1];
        $name = md5($tmp);
        $i = 1;
        while (file_exists($path . $name . '.' . $end)) $name = md5($name . time() . $i++);
        move_uploaded_file($tmp, $path . $name . '.' . $end);
        return $name . '.' . $end;
        }

# ПОЛУЧЕНИЕ ДАННЫХ ПО РЕЗЮМЕ ######################################################################################################
    function JM_get_data_resume()
        {
        # переменные
            $return = [];
            $sql =['','',''];
        # получение данных 
            JM_connect_db();
            $table = $GLOBALS['JM']['DB_PREFIX'] . 'resume';
            $sql[0] = "SELECT `id`, `time`, `value` FROM `" . $table . "`";
            if (isset($_GET['id']))
                {
                $id = $GLOBALS['JM']['DB_LINK'] -> real_escape_string($_GET['id']);
                $sql[1] = "WHERE `id` = '" . $id . "'";
                }
            $sql[2] = "ORDER BY `time` DESC;";
            $res = $GLOBALS['JM']['DB_LINK'] -> query(implode(' ', $sql));
            while ($row = $res -> fetch_assoc())
                {
                $row['value'] = json_decode($row['value'], JSON_OBJECT_AS_ARRAY);
                if (is_array($row['value']))
                    {
                    $return[$row['id']]['name'] = $row['value']['SecondName'] . ' ' . $row['value']['FirstName'];
                    $return[$row['id']]['org'] = $row['value']['OrganizationBin'];
                    $return[$row['id']]['time'] = $row['time'];
                    $return[$row['id']]['data'] = $row['value'];
                    }
                }
        # возврат результата
            return $return;
        }

# ПОДГОТОВКА ДАННЫХ ДЛЯ ВЫГРУЗКИ ##################################################################################################
    function JM_get_data_resume_for_1C()
        {
        # переменные
            $return = [];
            $sql =['','',''];
            $server = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . '/vacancy/file.php?file=';
        # получение данных 
            JM_connect_db();
            $table = $GLOBALS['JM']['DB_PREFIX'] . 'resume';
        # проверка временной метки
            if (isset($_GET['date'])) $date = $GLOBALS['JM']['DB_LINK'] -> real_escape_string((int) $_GET['date']);
            else $date = 0;
        # запрос данных
            $Gid_Bin = JM_set_gid_to_bin('Gid_Bin');
            
            $sql = "SELECT `value` FROM `" . $table . "` WHERE UNIX_TIMESTAMP(`time`) >= " . $date . ";";
            $res = $GLOBALS['JM']['DB_LINK'] -> query($sql);
            while ($row = $res -> fetch_assoc())
                {
                $row = json_decode($row['value'], JSON_OBJECT_AS_ARRAY);
                if (!is_array($row)) continue;
                $row = JM_strip_tags($row);
                $arr = [];
                
                $arr['OrganizationBin'] = $Gid_Bin[$row['OrganizationBin']];

                $arr['VacancyId'] = '';
                $arr['ContestId'] = '';
                if ($row['VacancyId'] && $row['IsContest']) $arr['ContestId'] = $row['VacancyId'];
                if ($row['VacancyId'] && !$row['IsContest']) $arr['VacancyId'] = $row['VacancyId'];

                $arr['FirstName'] = $row['FirstName'];
                $arr['SecondName'] = $row['SecondName'];
                $arr['Iin'] = $row['Iin'];
                $arr['BirthDate'] = $row['BirthDate'];
                $arr['KazakhLevel'] = $row['KazakhLevel'];
                $arr['RussianLevel'] = $row['RussianLevel'];
                $arr['EnglishLevel'] = $row['EnglishLevel'];

                foreach ($row['Education'] as $e)
                    {
                    unset($e['EducationLevelIdHand']);
                    unset($e['EducationOrganizationHand']);
                    unset($e['CityHand']);
                    $e['IsBolashak'] = (boolean) $e['IsBolashak'];
                    $arr['Education'][] = $e;
                    }

                $arr['TotalExperience'] = $row['TotalExperience'];
                $arr['EducationExperience'] = $row['EducationExperience'];
                $arr['Phone'] = $row['Phone'];
                $arr['Email'] = $row['Email'];
                $arr['ActualCity'] = $row['ActualCity'];

                foreach ($row['SummaryFiles'] as $file) $arr['SummaryFiles'][] = $server .  $file;
                
                if ($row['IsContest'])
                    {
                    $arr['TestingLanguage'] = $row['TestingLanguage'];
                    $arr['Subject'] = $row['Subject'];
                    
                    }

                $return[] = $arr;
                }
        # возврат    
            return json_encode($return, JSON_UNESCAPED_UNICODE);
        }

# УДАЛЕНИЕ HTML ###################################################################################################################
    function JM_strip_tags($arr)
        {
        if (is_array($arr))
            {
            foreach ($arr as $k => $e) $arr[$k] = JM_strip_tags($e);
            return $arr;
            }
        else return strip_tags($arr);
        }

# EXCEL - ГЕНЕРАЦИЯ ФАЙЛА #########################################################################################################
    function JM_excel_get()
        {
        # подготовка данных
            $Gid_Bin = JM_set_gid_to_bin('Gid_Bin');
            $s['job'] = JM_get_data_job('all');
            $s['subject'] = JM_get_data_list('subject', 'ShortName');
            $s['edu_lvl'] = JM_get_data_list('education-lvl');
            $s['edu_org'] = JM_get_data_list('education-org');
            $s['country'] = JM_get_data_list('country');
            $s['city'] = JM_get_data_city();
            $s['lang'] = [1 => 'Казахский', 2 => 'Русский', 3 => 'Английский'];
            $arrData = ['WISH' => [], 'VACANCY' => [], 'CONTEST'=> []];
            $arrFile = ['WISH' => 1, 'CONTEST' => 1, 'VACANCY' => 1];
            $c = 0;
            $v = 0;        
            
            foreach ($GLOBALS['JM']['HTML']['DATA_RESUME'] as $k => $item)
                {
                if ($item['data']['IsContest'] === '' && $item['data']['VacancyId'] === '')
                    {
                    $arrData['WISH'][] = $item;
                    if ($arrFile['WISH'] < count($item['data']['SummaryFiles'])) $arrFile['WISH'] = count($item['data']['SummaryFiles']);
                    }
                else
                    {
                    if ($item['data']['IsContest'] !== '')
                        {
                        $arrData['CONTEST'][$c++] = $item;
                        if ($arrFile['CONTEST'] < count($item['data']['SummaryFiles'])) $arrFile['CONTEST'] = count($item['data']['SummaryFiles']);
                        $type = 'contest';
                        }
                    else 
                        {
                        $arrData['VACANCY'][$v++] = $item;
                        if ($arrFile['VACANCY'] < count($item['data']['SummaryFiles'])) $arrFile['VACANCY'] = count($item['data']['SummaryFiles']);
                        $type = 'vacancy';
                        }
                    if (count($s['job']) !== 0)
                        {
                        foreach ($s['job'][$Gid_Bin[$item['data']['OrganizationBin']]][$type] as $e)
                            {
                            if ($item['data']['VacancyId'] == $e['Id'])
                                {
                                if ($type === 'contest')
                                    {
                                    $arrData['CONTEST'][$c - 1]['position'] = $e['NameRu'];
                                    $arrData['CONTEST'][$c - 1]['departament'] = $e['DepartmentRu'];
                                    }
                                if ($type === 'vacancy')
                                    {
                                    $arrData['VACANCY'][$v - 1]['position'] = $e['NameRu'];
                                    $arrData['VACANCY'][$v - 1]['departament'] = $e['DepartmentRu'];
                                    }
                                }
                            }
                        }
                    }
                }

        # инициализация библиотеки
            $ds = DIRECTORY_SEPARATOR;
            require_once __DIR__ . $ds . 'lib' . $ds . 'PHPExcel' . $ds . 'PHPExcel.php';
            require_once __DIR__ . $ds . 'lib' . $ds . 'PHPExcel' . $ds . 'PHPExcel' . $ds . 'Writer' . $ds . 'Excel5.php';
            $xls = new PHPExcel();
            $i_sheet = 0;
            $arr = range('A', 'Z');
            $arrCol = $arr;
            foreach ($arr as $x) foreach ($arr as $y) $arrCol[] = $x . $y;
        
        # стили оформления
            $bg = [
                'fill' => [
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => ['rgb' => 'CCCCCC']
                ]
            ];

            $link = [
                'font' => [
                        'color' => ['rgb' => '0000FF'], 
                        'underline' => 'single'
                ]
            ];

            $border = [
                'borders' => [
                    'allborders' => [
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                        'color' => ['rgb' => '000000']
                    ]
                ]
            ];

        # шаблоны заголовков
            $arrHead_wish = [
                'Описание' => ['Добавлено','Организация','Предмет обучения','Язык преподавания'],
                'Общая информация' => ['Имя','Фамилия','Дата рождения','ИИН'],
                'Владение языками' => ['Казахский','Русский','Английский'],
                'Стаж работы' => ['Общий','В образовании'],
                'Контактные данные' => ['Телефон','E-mail','Город'],
                'Файлы' => $arrFile['WISH'],
            ];
            $arrHead_vacancy = [
                'Описание' => ['Добавлено','Организация','Подразделение','Должность'],
                'Общая информация' => ['Имя','Фамилия','Дата рождения','ИИН'],
                'Владение языками' => ['Казахский','Русский','Английский'],
                'Стаж работы' => ['Общий','В образовании'],
                'Контактные данные' => ['Телефон','E-mail','Город'],
                'Файлы' => $arrFile['VACANCY'],
            ];
            $arrHead_contest = [
                'Описание' => ['Добавлено','Организация','Подразделение','Должность'],
                'Для конкурса' => ['Язык сдачи','Предмет','Удостоверение','Диплом','Трудовая книжка'],
                'Общая информация' => ['Имя','Фамилия','Дата рождения','ИИН'],
                'Владение языками' => ['Казахский','Русский','Английский'],
                'Стаж работы' => ['Общий','В образовании'],
                'Контактные данные' => ['Телефон','E-mail','Город'],
                'Файлы' => $arrFile['CONTEST'],
            ];
            $arrHeadEdu = [
                'Тип образования','Дата начала','Дата окончания',
                'Учебное заведение','Страна','Город','По болашаку',
                'Специальность','Номер диплома','Тема дипломной работы',
                'Примечание'
            ];

        # генерация EXCEL вкладка ХОЧУ ТУТ РАБОТАТЬ
            if (count($arrData['WISH']) !== 0)
                {
                if ($i_sheet !== 0) $xls -> createSheet();
                $xls -> setActiveSheetIndex($i_sheet++);
                $sheet = $xls -> getActiveSheet();
                $sheet -> setTitle('ХОЧУ ТУТ РАБОТАТЬ');
                # вставка шапки
                    $col_head = 0;            
                    $col_head = JM_excel_set_head($arrCol, $sheet, $col_head, $arrHead_wish);
                    $row = 3;
                    $arr_edu = [];
                    $max_col = 0;
                # вставка данных
                    foreach ($arrData['WISH'] as $item)
                        {
                        $col = 0;
                        if ($item['data']['ActualCity'] === '') $item['data']['ActualCity'] = '* ' . $item['data']['ActualCityHand'];
                        else $item['data']['ActualCity'] = $s['city'][$item['data']['ActualCity']];
                        $item['org'] = $GLOBALS['JM']['HTML']['DATA_ORG'][$item['org']]['long_name'];

                        $sheet -> getColumnDimension($arrCol[$col]) -> setWidth(12);
                        $sheet -> setCellValueExplicit($arrCol[$col++] . $row, $item['time'], PHPExcel_Cell_DataType::TYPE_STRING);
                        $sheet -> getColumnDimension($arrCol[$col]) -> setWidth(30);
                        $sheet -> setCellValueExplicit($arrCol[$col++] . $row, $item['org'], PHPExcel_Cell_DataType::TYPE_STRING);
                        $sheet -> getColumnDimension($arrCol[$col]) -> setWidth(20);
                        $sheet -> setCellValueExplicit($arrCol[$col++] . $row, $item['data']['Temp_2'], PHPExcel_Cell_DataType::TYPE_STRING);
                        $sheet -> getColumnDimension($arrCol[$col]) -> setWidth(20);
                        $sheet -> setCellValueExplicit($arrCol[$col++] . $row, $item['data']['Temp_3'], PHPExcel_Cell_DataType::TYPE_STRING);

                        $col = JM_excel_set_data($arrCol, $sheet, $col, $row, $item);

                        JM_excel_set_file($arrCol, $sheet, $col, $row, $item['data']['SummaryFiles'], $link);
                        $col = $col + $arrFile['WISH'];

                        $i_edu = '1';
                        foreach ($item['data']['Education'] as $edu)
                            {
                            if (!in_array($i_edu, $arr_edu))
                                {
                                $col_head = JM_excel_set_head($arrCol, $sheet, $col_head, ['Образование №' . $i_edu => $arrHeadEdu]);
                                $arr_edu[] = $i_edu;
                                }
                            $i_edu++;
                            $col = JM_excel_set_edu($arrCol, $sheet, $col, $row, $edu, $s, $r);
                            }
                        $row++;
                        if ($col > $max_col) $max_col = $col;
                        }

                $max_col--;
                $row--;
                $sheet -> getStyle('A1:' . $arrCol[$max_col] . '2') -> applyFromArray($bg);
                $sheet -> getStyle('A1:' . $arrCol[$max_col] . '2') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $sheet -> getStyle('A1:' . $arrCol[$max_col] . '2') -> getAlignment() -> setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $sheet -> getStyle('A1:' . $arrCol[$max_col] . '2') -> getFont() -> setBold(true);
        
                $sheet -> getStyle('A3:' . $arrCol[$max_col] . $row) -> getAlignment() -> setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);

                for ($i = 0; $i <= $row; $i++) $sheet -> getRowDimension($i) -> setRowHeight(-1);

                $sheet -> getStyle('A1:' . $arrCol[$max_col] . $row) -> applyFromArray($border);
                $sheet -> getStyle('A1:' . $arrCol[$max_col] . $row) -> getAlignment() -> setWrapText(true);

                }

        # генерация EXCEL вкладка ВАКАНСИИ
            if (count($arrData['VACANCY']) !== 0)
                {
                if ($i_sheet !== 0) $xls -> createSheet();
                $xls -> setActiveSheetIndex($i_sheet++);
                $sheet = $xls -> getActiveSheet();
                $sheet -> setTitle('ВАКАНСИИ');
                $max_col = 0;
                # вставка шапки
                    $col_head = 0;
                    $col_head = JM_excel_set_head($arrCol, $sheet, $col_head, $arrHead_vacancy);
                    $row = 3;
                    $arr_edu = [];
                # вставка данных
                    foreach ($arrData['VACANCY'] as $item)
                        {
                        $col = 0;
                        if ($item['data']['ActualCity'] === '') $item['data']['ActualCity'] = '* ' . $item['data']['ActualCityHand'];
                        else $item['data']['ActualCity'] = $s['city'][$item['data']['ActualCity']];
                        $item['org'] = $GLOBALS['JM']['HTML']['DATA_ORG'][$item['org']]['long_name'];

                        $sheet -> getColumnDimension($arrCol[$col]) -> setWidth(12);
                        $sheet -> setCellValueExplicit($arrCol[$col++] . $row, $item['time'], PHPExcel_Cell_DataType::TYPE_STRING);
                        $sheet -> getColumnDimension($arrCol[$col]) -> setWidth(30);
                        $sheet -> setCellValueExplicit($arrCol[$col++] . $row, $item['org'], PHPExcel_Cell_DataType::TYPE_STRING);
                        $sheet -> getColumnDimension($arrCol[$col]) -> setWidth(20);
                        $sheet -> setCellValueExplicit($arrCol[$col++] . $row, $item['departament'], PHPExcel_Cell_DataType::TYPE_STRING);
                        $sheet -> getColumnDimension($arrCol[$col]) -> setWidth(20);
                        $sheet -> setCellValueExplicit($arrCol[$col++] . $row, $item['position'], PHPExcel_Cell_DataType::TYPE_STRING);

                        $col = JM_excel_set_data($arrCol, $sheet, $col, $row, $item);

                        JM_excel_set_file($arrCol, $sheet, $col, $row, $item['data']['SummaryFiles'], $link);
                        $col = $col + $arrFile['VACANCY'];

                        $i_edu = '1';
                        foreach ($item['data']['Education'] as $edu)
                            {
                            if (!in_array($i_edu, $arr_edu))
                                {
                                $col_head = JM_excel_set_head($arrCol, $sheet, $col_head, ['Образование №' . $i_edu => $arrHeadEdu]);
                                $arr_edu[] = $i_edu;
                                }
                            $i_edu++;
                            $col = JM_excel_set_edu($arrCol, $sheet, $col, $row, $edu, $s, $r);
                            }
                        $row++;
                        if ($col > $max_col) $max_col = $col;
                        }

                $max_col--;
                $row--;
                $sheet -> getStyle('A1:' . $arrCol[$max_col] . '2') -> applyFromArray($bg);
                $sheet -> getStyle('A1:' . $arrCol[$max_col] . '2') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $sheet -> getStyle('A1:' . $arrCol[$max_col] . '2') -> getAlignment() -> setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $sheet -> getStyle('A1:' . $arrCol[$max_col] . '2') -> getFont() -> setBold(true);
        
                $sheet -> getStyle('A3:' . $arrCol[$max_col] . $row) -> getAlignment() -> setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);

                for ($i = 0; $i <= $row; $i++) $sheet -> getRowDimension($i) -> setRowHeight(-1);

                $sheet -> getStyle('A1:' . $arrCol[$max_col] . $row) -> applyFromArray($border);
                $sheet -> getStyle('A1:' . $arrCol[$max_col] . $row) -> getAlignment() -> setWrapText(true);

                }

        # генерация EXCEL вкладка КОНКУРСЫ
            if (count($arrData['CONTEST']) !== 0)
                {
                if ($i_sheet !== 0) $xls -> createSheet();
                $xls -> setActiveSheetIndex($i_sheet++);
                $sheet = $xls -> getActiveSheet();
                $sheet -> setTitle('КОНКУРСЫ');
                # вставка шапки
                    $col_head = 0;
                    $col_head = JM_excel_set_head($arrCol, $sheet, $col_head, $arrHead_contest);
                    $row = 3;
                    $arr_edu = [];
                    $max_col = 0;
                # вставка данных
                foreach ($arrData['CONTEST'] as $item)
                        {
                        $col = 0;
                        if ($item['data']['Subject'] === '') $item['data']['Subject'] = '* ' . $item['data']['SubjectHand'];
                        else $item['data']['Subject'] = $s['subject'][$item['data']['Subject']];
                        if ($item['data']['ActualCity'] === '') $item['data']['ActualCity'] = '* ' . $item['data']['ActualCityHand'];
                        else $item['data']['ActualCity'] = $s['city'][$item['data']['ActualCity']];
                        $item['org'] = $GLOBALS['JM']['HTML']['DATA_ORG'][$item['org']]['long_name'];

                        $sheet -> getColumnDimension($arrCol[$col]) -> setWidth(12);
                        $sheet -> setCellValueExplicit($arrCol[$col++] . $row, $item['time'], PHPExcel_Cell_DataType::TYPE_STRING);
                        $sheet -> getColumnDimension($arrCol[$col]) -> setWidth(30);
                        $sheet -> setCellValueExplicit($arrCol[$col++] . $row, $item['org'], PHPExcel_Cell_DataType::TYPE_STRING);
                        $sheet -> getColumnDimension($arrCol[$col]) -> setWidth(20);
                        $sheet -> setCellValueExplicit($arrCol[$col++] . $row, $item['departament'], PHPExcel_Cell_DataType::TYPE_STRING);
                        $sheet -> getColumnDimension($arrCol[$col]) -> setWidth(20);
                        $sheet -> setCellValueExplicit($arrCol[$col++] . $row, $item['position'], PHPExcel_Cell_DataType::TYPE_STRING);
                        $sheet -> getColumnDimension($arrCol[$col]) -> setWidth(20);
                        $sheet -> setCellValueExplicit($arrCol[$col++] . $row, $s['lang'][$item['data']['TestingLanguage']], PHPExcel_Cell_DataType::TYPE_STRING);
                        $sheet -> getColumnDimension($arrCol[$col]) -> setWidth(20);
                        $sheet -> setCellValueExplicit($arrCol[$col++] . $row, $item['data']['Subject'], PHPExcel_Cell_DataType::TYPE_STRING);
                        $sheet -> getColumnDimension($arrCol[$col]) -> setWidth(20);
                        $sheet -> setCellValueExplicit($arrCol[$col] . $row, 'ФАЙЛ', PHPExcel_Cell_DataType::TYPE_STRING);
                             $sheet -> getColumnDimension($arrCol[$col]) -> setWidth(20);
                        $sheet -> setCellValueExplicit($arrCol[$col] . $row, 'ФАЙЛ', PHPExcel_Cell_DataType::TYPE_STRING);
                        $sheet -> getColumnDimension($arrCol[$col]) -> setWidth(20);
                        $sheet -> setCellValueExplicit($arrCol[$col] . $row, 'ФАЙЛ', PHPExcel_Cell_DataType::TYPE_STRING);
                         $col = JM_excel_set_data($arrCol, $sheet, $col, $row, $item);

                        JM_excel_set_file($arrCol, $sheet, $col, $row, $item['data']['SummaryFiles'], $link);
                        $col = $col + $arrFile['CONTEST'];

                        $i_edu = '1';
                        foreach ($item['data']['Education'] as $edu)
                            {
                            if (!in_array($i_edu, $arr_edu))
                                {
                                $col_head = JM_excel_set_head($arrCol, $sheet, $col_head, ['Образование №' . $i_edu => $arrHeadEdu]);
                                $arr_edu[] = $i_edu;
                                }
                            $i_edu++;
                            $col = JM_excel_set_edu($arrCol, $sheet, $col, $row, $edu, $s, $r);
                            }
                        $row++;
                        if ($col > $max_col) $max_col = $col;          
                        }

                $max_col--;
                $row--;
                $sheet -> getStyle('A1:' . $arrCol[$max_col] . '2') -> applyFromArray($bg);
                $sheet -> getStyle('A1:' . $arrCol[$max_col] . '2') -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $sheet -> getStyle('A1:' . $arrCol[$max_col] . '2') -> getAlignment() -> setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $sheet -> getStyle('A1:' . $arrCol[$max_col] . '2') -> getFont() -> setBold(true);
        
                $sheet -> getStyle('A3:' . $arrCol[$max_col] . $row) -> getAlignment() -> setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
                
                for ($i = 0; $i <= $row; $i++) $sheet -> getRowDimension($i) -> setRowHeight(-1);

                $sheet -> getStyle('G3:I' . $row) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $sheet -> getStyle('G3:I' . $row) -> applyFromArray($link);

                $sheet -> getStyle('A1:' . $arrCol[$max_col] . $row) -> applyFromArray($border);
                $sheet -> getStyle('A1:' . $arrCol[$max_col] . $row) -> getAlignment() -> setWrapText(true);
                }

        header("Expires: Mon, 1 Apr 1974 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=РЕЗЮМЕ-" . date('Y-m-d-H-i-s') . ".xls");
        $xls -> setActiveSheetIndex(0) -> getCell('A1');
        $objWriter = new PHPExcel_Writer_Excel5($xls);
        $objWriter -> save('php://output'); 
        exit();	
        }

# EXCEL - ГЕНЕРАЦИЯ ШАПКИ #########################################################################################################
    function JM_excel_set_head($arrCol, $sheet, $col, $arrData)
        {
        foreach ($arrData as $k => $e)
            {
            if (is_array($e))
                {
                $sheet -> mergeCells($arrCol[$col] . '1:' . $arrCol[$col + count($e) - 1] . '1');
                $sheet -> setCellValueExplicit($arrCol[$col] . '1', $k, PHPExcel_Cell_DataType::TYPE_STRING);
                foreach ($e as $ee) 
                    {
                    $sheet -> setCellValueExplicit($arrCol[$col++] . '2', $ee, PHPExcel_Cell_DataType::TYPE_STRING);
                    }
                }
            else 
                {
                $sheet -> mergeCells($arrCol[$col] . '1:' . $arrCol[$col + $e - 1] . '2');
                $sheet -> setCellValueExplicit($arrCol[$col] . '1', $k, PHPExcel_Cell_DataType::TYPE_STRING);
                $col = $col + $e;
                }
            }
        return $col;
        }

# EXCEL - ГЕНЕРАЦИЯ ДАННЫХ ########################################################################################################
    function JM_excel_set_data($arrCol, $sheet, $col, $row, $item)
        {
        $sheet -> getColumnDimension($arrCol[$col]) -> setWidth(20);
        $sheet -> setCellValueExplicit($arrCol[$col++] . $row, $item['data']['FirstName'], PHPExcel_Cell_DataType::TYPE_STRING);
        $sheet -> getColumnDimension($arrCol[$col]) -> setWidth(20);
        $sheet -> setCellValueExplicit($arrCol[$col++] . $row, $item['data']['SecondName'], PHPExcel_Cell_DataType::TYPE_STRING);
        $sheet -> getColumnDimension($arrCol[$col]) -> setWidth(20);
        $sheet -> setCellValueExplicit($arrCol[$col++] . $row, $item['data']['BirthDate'], PHPExcel_Cell_DataType::TYPE_STRING);
        $sheet -> getColumnDimension($arrCol[$col]) -> setWidth(20);
        $sheet -> setCellValueExplicit($arrCol[$col++] . $row, $item['data']['Iin'], PHPExcel_Cell_DataType::TYPE_STRING);
        $sheet -> getColumnDimension($arrCol[$col]) -> setWidth(20);
        $sheet -> setCellValueExplicit($arrCol[$col++] . $row, $item['data']['KazakhLevel'], PHPExcel_Cell_DataType::TYPE_STRING);
        $sheet -> getColumnDimension($arrCol[$col]) -> setWidth(20);
        $sheet -> setCellValueExplicit($arrCol[$col++] . $row, $item['data']['RussianLevel'], PHPExcel_Cell_DataType::TYPE_STRING);
        $sheet -> getColumnDimension($arrCol[$col]) -> setWidth(20);
        $sheet -> setCellValueExplicit($arrCol[$col++] . $row, $item['data']['EnglishLevel'], PHPExcel_Cell_DataType::TYPE_STRING);
        $sheet -> getColumnDimension($arrCol[$col]) -> setWidth(20);
        $sheet -> setCellValueExplicit($arrCol[$col++] . $row, $item['data']['TotalExperience'], PHPExcel_Cell_DataType::TYPE_STRING);
        $sheet -> getColumnDimension($arrCol[$col]) -> setWidth(20);
        $sheet -> setCellValueExplicit($arrCol[$col++] . $row, $item['data']['EducationExperience'], PHPExcel_Cell_DataType::TYPE_STRING);
        $sheet -> getColumnDimension($arrCol[$col]) -> setWidth(20);
        $sheet -> setCellValueExplicit($arrCol[$col++] . $row, $item['data']['Phone'], PHPExcel_Cell_DataType::TYPE_STRING);
        $sheet -> getColumnDimension($arrCol[$col]) -> setWidth(20);
        $sheet -> setCellValueExplicit($arrCol[$col++] . $row, $item['data']['Email'], PHPExcel_Cell_DataType::TYPE_STRING);
        $sheet -> getColumnDimension($arrCol[$col]) -> setWidth(20);
        $sheet -> setCellValueExplicit($arrCol[$col++] . $row, $item['data']['ActualCity'], PHPExcel_Cell_DataType::TYPE_STRING);

        return $col;
        }

# EXCEL - ГЕНЕРАЦИЯ ССЫЛОК ########################################################################################################
    function JM_excel_set_file($arrCol, $sheet, $col, $row, $item, $link)
        {
        foreach ($item as $file)
            {
            $sheet -> setCellValueExplicit($arrCol[$col] . $row, 'ФАЙЛ', PHPExcel_Cell_DataType::TYPE_STRING);
            $sheet -> getStyle($arrCol[$col] . $row) -> getAlignment() -> setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet -> getStyle($arrCol[$col] . $row) -> applyFromArray($link);
            $sheet -> getColumnDimension($arrCol[$col]) -> setWidth(10);
            $sheet -> getCell($arrCol[$col++] . $row) -> getHyperlink() -> setUrl($_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/vacancy/file.php?file=' . $file);          
            }
        }

# EXCEL - ГЕНЕРАЦИЯ РАЗДЕЛА ОБРАЗОВАНИЕ ###########################################################################################
    function JM_excel_set_edu($arrCol, $sheet, $col, $row, $edu, $s, $r)
        {
        if ($edu['EducationLevelId'] === '') $edu['EducationLevelId'] = '* ' . $edu['EducationLevelIdHand'];
        else $edu['EducationLevelId'] = $s['edu_lvl'][$edu['EducationLevelId']];
        
        if ($edu['EducationOrganization'] === '') $edu['EducationOrganization'] = '* ' . $edu['EducationOrganizationHand'];
        else $edu['EducationOrganization'] = $s['edu_org'][$edu['EducationOrganization']];

        $edu['Country'] = $s['country'][$edu['Country']];

        if ($edu['City'] === '') $edu['City'] = '* ' . $edu['CityHand'];
        else $edu['City'] = $s['city'][$edu['City']];

        if ((boolean) $edu['IsBolashak']) $edu['IsBolashak'] = 'Да';
        else $edu['IsBolashak'] = 'Нет';

        $sheet -> getColumnDimension($arrCol[$col]) -> setWidth(20);
        $sheet -> setCellValueExplicit($arrCol[$col++] . $row, $edu['EducationLevelId'], PHPExcel_Cell_DataType::TYPE_STRING);
        $sheet -> getColumnDimension($arrCol[$col]) -> setWidth(20);
        $sheet -> setCellValueExplicit($arrCol[$col++] . $row, $edu['StartDate'], PHPExcel_Cell_DataType::TYPE_STRING);
        $sheet -> getColumnDimension($arrCol[$col]) -> setWidth(20);
        $sheet -> setCellValueExplicit($arrCol[$col++] . $row, $edu['FinishDate'], PHPExcel_Cell_DataType::TYPE_STRING);
        $sheet -> getColumnDimension($arrCol[$col]) -> setWidth(20);
        $sheet -> setCellValueExplicit($arrCol[$col++] . $row, $edu['EducationOrganization'], PHPExcel_Cell_DataType::TYPE_STRING);
        $sheet -> getColumnDimension($arrCol[$col]) -> setWidth(20);
        $sheet -> setCellValueExplicit($arrCol[$col++] . $row, $edu['Country'], PHPExcel_Cell_DataType::TYPE_STRING);
        $sheet -> getColumnDimension($arrCol[$col]) -> setWidth(20);
        $sheet -> setCellValueExplicit($arrCol[$col++] . $row, $edu['City'], PHPExcel_Cell_DataType::TYPE_STRING);
        $sheet -> getColumnDimension($arrCol[$col]) -> setWidth(20);
        $sheet -> setCellValueExplicit($arrCol[$col++] . $row, $edu['IsBolashak'], PHPExcel_Cell_DataType::TYPE_STRING);
        $sheet -> getColumnDimension($arrCol[$col]) -> setWidth(20);
        $sheet -> setCellValueExplicit($arrCol[$col++] . $row, $edu['Specialty'], PHPExcel_Cell_DataType::TYPE_STRING);
        $sheet -> getColumnDimension($arrCol[$col]) -> setWidth(20);
        $sheet -> setCellValueExplicit($arrCol[$col++] . $row, $edu['DiplomaNumber'], PHPExcel_Cell_DataType::TYPE_STRING);
        $sheet -> getColumnDimension($arrCol[$col]) -> setWidth(20);
        $sheet -> setCellValueExplicit($arrCol[$col++] . $row, $edu['WorkTheme'], PHPExcel_Cell_DataType::TYPE_STRING);
        $sheet -> getColumnDimension($arrCol[$col]) -> setWidth(20);
        $sheet -> setCellValueExplicit($arrCol[$col++] . $row, $edu['Note'], PHPExcel_Cell_DataType::TYPE_STRING);
        
        return $col;
        }