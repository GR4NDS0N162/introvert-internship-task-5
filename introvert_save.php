<?php
//Загрузка данных в amoCRM (by INTROVERT)

$intr_key = '3363f0c5';
$introvertUrl = 'https://api.yadrocrm.ru/integration/site?key=' . $intr_key;

$cookieData = [];
if (isset($_COOKIE['introvert_cookie'])) {
    $cookieData = json_decode($_COOKIE['introvert_cookie'], true) ?: []; // данные сохраняемые js скриптом
}

$postArr = array_merge($cookieData, $_POST); // $_POST данные отправленной формы
// объединяем данные и отправляем

if (function_exists('curl_init')) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $introvertUrl);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postArr));
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_USERAGENT, 'Yadro-Site-Integration-client/1.0');
    $result = curl_exec($curl);
    curl_close($curl);
} else {
    if (ini_get('allow_url_fopen')) {
        $opts = ['http' =>
            [
                'method' => 'POST',
                'header' => 'Content-type: application/x-www-form-urlencoded',
                'content' => http_build_query($postArr),
                'timeout' => 2,
            ],
        ];

        try {
            file_get_contents($introvertUrl, false, stream_context_create($opts));
        } catch (Exception $e) {
            return;
        }
    }
}
