<?php

# параметры
    include_once '.conf.php';
    
# проверка входящего параметра
    if (!isset($_GET['file']) || $_GET['file'] === '') exit;
    $_GET['file'] = str_replace(['/','\\','..'], '', $_GET['file']);
    $file = $GLOBALS['JM']['STORAGE'] . 'files' . DIRECTORY_SEPARATOR . $_GET['file'];

# проверка наличия файла
    if (!file_exists($file)) exit;
    
# сброс буфера
    if (ob_get_level()) ob_end_clean();

# заставляем браузер показать окно сохранения файла
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . basename($file));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));

# читаем файл и отправляем его пользователю
    readfile($file);
    exit;


