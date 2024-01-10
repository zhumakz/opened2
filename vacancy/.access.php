<?php

# проверка доступа
    if ($GLOBALS['token_check'])
        {
        if (!isset($_GET['token']) || $_GET['token'] !== $GLOBALS['token'])
            {
            $return['status'] = 'error';
            $return['error'] = 'Неверный токен';
            header('Content-type: application/json; charset=utf-8');
            echo json_encode($return, JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE);
            exit;
            }
        }