<?php

$url = 'http://career.nis.edu.kz/vacancy/data_in.php?token=1234567890';

$result = file_get_contents($url, false, stream_context_create([
    'http' => [
        'method'  => 'POST',
        'header'  => 'Accept: application/json',
        'content' => file_get_contents('data.json')
    ]
]));

echo $result;