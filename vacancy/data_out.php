<?php

# параметры
    include_once '.conf.php';

# проверка доступа
    include_once '.access.php';

# подключение библиотеки функций
    include_once '.func.php';

# возврат данных
    header('Content-type: application/json; charset=utf-8');
    echo JM_get_data_resume_for_1C();