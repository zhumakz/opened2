<?php

# параметры
    include_once '.conf.php';

# проверка доступа
    include_once '.access.php';

# подключение библиотеки функций
    include_once '.func.php';

# чтение потока
    $data = fopen('php://input', 'r');
    $data = stream_get_contents($data);
    file_put_contents(__DIR__ . '/data/log/.log-data-in-input', $data);

# предобработка строки
    if (substr($data, 0, 3) == "\xEF\xBB\xBF") $data = str_replace("\xEF\xBB\xBF", '', $data);
    if (substr($data, 0, 2) == "\xFF\xFE") $data = str_replace("\xFF\xFE", '', $data);
    $data = str_replace('\n', '<br />', $data);

# обработка данных
    $return = [];
    $data = JM_decode(json_decode($data, JSON_OBJECT_AS_ARRAY));
    if (JM_check_struct_data_in($data))
        {
        JM_set_data_job($data['Data']);
        $return['status'] = 'ok';
        }
    else
        {
        $return['status'] = 'error';
        $return['error'] = 'Ошибка структуры';
        }

# возврат статуса
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($return, JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE);