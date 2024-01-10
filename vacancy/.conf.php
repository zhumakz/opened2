<?php

# вывод ошибок
    ini_set('error_reporting', E_ALL);
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    
# часовая зона
    date_default_timezone_set('Asia/Almaty');

# параметры доступа
    $GLOBALS['token_check'] = true;                # параметр проверки token
    $GLOBALS['token'] = '1234567890';              # token доступа

# токен доступа к API
    $GLOBALS['token_api'] = 'eyJhbGciOiJBMjU2S1ciLCJlbmMiOiJBMjU2Q0JDLUhTNTEyIiwidHlwIjoiSldUIn0.-XZ2yq6ZYCi6RTzvna-6hP0kWRMGcSNCtk76fTB2CAXXSjGLoGkjGDTlBa-PNKlW-q7XptF0bw25sAB6IBje0ZHQjr0nzmPr.xdpq-brRQngQQbwGDx6XhA.g0RtUCanUZeRA7u7nE9r0qJ6xF5b2-gw6amH2GMC8xhOoziOhoWxEmf7_TEHG6US_y6GyRw0WTWKDKSnLUcvhFEks2570K1Jsc91vcPlcXag1xa2-2xLzI3ggEKCFajx7oXdgDNinj3iou4zn_9FqQgYpPp4BCV4LxB9Nh3lGR9zLmOxH88ng22RJbxwd6fWfYdQXyQtLlkxisJ4AaKZMJhadyxJNM6AXAP_ucg-T6crXFd5sFXmtpxTBDZMNe35zZt_9PEu88dFlM8pWyFWeUbSZyhmO8ffa13Lttrh7dWx0g86A_utrkAASoat3gCOX_Pa9VB9rYNqOLtlxMxt9sHlp7o14juMxLW3MWNKGMQDjedyEN9_WdjcBB6EMoLFSZOzze-5-L8wre0YvwVyeVHeVkJoP65-3vBF8dkgMxo.o3JDo-USiz1AlBl18nRaL9VoIA32iaGxNWYGRm-jrhM';

	# параметры подключения к БД
    $GLOBALS['JM']['DB_PREFIX'] = '';
    $GLOBALS['JM']['DB_HOST'] = 'localhost';
    $GLOBALS['JM']['DB_USER'] = 'career';
    $GLOBALS['JM']['DB_PASS'] = 'Pass@uk11';
    $GLOBALS['JM']['DB_NAME'] = 'career';
    $GLOBALS['JM']['DB_PORT'] = '3306';
    $GLOBALS['JM']['DB_LINK'] = new mysqli;

# параметры google капчи
    #$GLOBALS['JM']['CAPCHA_FRONTEND'] = '6Lf-qfoUAAAAAN-dgaLlXrrhqvnG91_SRBNTmP6P';
    #$GLOBALS['JM']['CAPCHA_BACKEND'] = '6Lf-qfoUAAAAAD6S5MHuHR7j6HpgrBD99uXfyP0c';
    $GLOBALS['JM']['CAPCHA_FRONTEND'] = '6Lf-qfoUAAAAAN-dgaLlXrrhqvnG91_SRBNTmP6P';
    $GLOBALS['JM']['CAPCHA_BACKEND'] = '6Lf-qfoUAAAAAD6S5MHuHR7j6HpgrBD99uXfyP0c';
    $GLOBALS['JM']['CAPCHA_CHECK'] = true;

# размещениe хранилища
    $GLOBALS['JM']['STORAGE'] = __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR;

# время кэширования данных из API (в секундах)
    $GLOBALS['JM']['API_CASH_TIME'] = 3600;       # организации

# использовать список организаций из API
    $GLOBALS['JM']['ORG_LIST_API'] = true;
# использовать пользовательский список организация
    $GLOBALS['JM']['ORG_LIST_USER'] = false;
# параметр сортировки списка организация
    $GLOBALS['JM']['ORG_LIST_SORT'] = false;
# параметр проверки наличия организаций
    $GLOBALS['JM']['ORG_ISSET'] = true;

# параметр отображения панели локализации
    $GLOBALS['JM']['LOCAL_TOOL'] = true;
# набор локализации
    $GLOBALS['JM']['LOCAL']['ru'] = '.ru.php';      # файл в папке lang с русской локализацией
    $GLOBALS['JM']['LOCAL']['kz'] = '.kz.php';      # файл в папке lang с казахской локализацией
    $GLOBALS['JM']['LOCAL']['en'] = '.en.php';      # файл в папке lang с английской локализацией
# определение текущего языка локализации
    if (!isset($GLOBALS['JM']['LOCAL'][$_GET['lang']])) $_GET['lang'] = 'ru';
# определение разных представлений параметра локали
    $GLOBALS['JM']['LOCAL_XX'] = mb_strtoupper($_GET['lang']);
    $GLOBALS['JM']['LOCAL_xx'] = mb_strtolower($_GET['lang']);
    $GLOBALS['JM']['LOCAL_Xx'] = ucfirst($_GET['lang']);