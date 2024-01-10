<?php

# параметры
    include_once '.conf.php';

# подключение библиотеки функций
    include_once '.func.php';

# обработка запроса
    if (isset($_GET['country']))
        {
        sleep(1);
        echo json_encode(JM_get_data_city($_GET['country']));
        }