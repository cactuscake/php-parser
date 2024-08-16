<?php

include 'simple_html_dom.php';

ini_set('max_execution_time', '10000');
set_time_limit(0);
ini_set('memory_limit', '2048M');
ignore_user_abort(true);

$ch = curl_init();

echo "<h1>Парсер</h1>";
$partnerCount = 1;
$projectCount = 1;

for ($i = 1; $i <= 120; $i++) {
    curl_setopt($ch, CURLOPT_URL, 'https://www.1c-bitrix.ru/partners/index_ajax.php?PAGEN_1=' . $i);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $response = curl_exec($ch);
    $data = str_get_html($response);
    foreach ($data->find('.bx-ui-tile__main-link') as $a) {
        $pageData = file_get_html('https://www.1c-bitrix.ru/partners/' . trim($a->getAttribute('href'), './'));
        file_put_contents(
            "log.txt",
            "Номер партнера: $partnerCount\n",
            FILE_APPEND
        );
        echo $pageData->find('.partner-card-profile-header-title', 0)->plaintext . "</br>";
        foreach ($pageData->find('.partner-project-pane__inner') as $a) {
            file_put_contents(
                "log.txt",
                "Номер проекта: $projectCount\n",
                FILE_APPEND
            );
            try {
                $productData = file_get_html("https://www.1c-bitrix.ru" . $a->getAttribute('href'));
            } catch (\Throwable $e) {
                echo "Не удалось получить страницу";
                file_put_contents(
                    "log.txt",
                    "Не удалось получить страницу",
                    FILE_APPEND
                );
                sleep(30);
                continue;
            }
            //$productData = file_get_html("https://www.1c-bitrix.ru" . $a->getAttribute('href'));

            if ($productData != false) {
                $link =  $productData->find('.detail-page-list__item-record_value', 3)->plaintext;
            } else {
                $link = "Не удалось получить ссылку";
                file_put_contents(
                    "log.txt",
                    "Не удалось получить ссылку",
                    FILE_APPEND
                );
            }

            if ($productData != false) {
                $redaction =  $productData->find('.detail-page-list__item-record_value', 2)->plaintext;
            } else {
                $redaction = "Не удалось получить редакцию";
                file_put_contents(
                    "log.txt",
                    "Не удалось получить редакцию",
                    FILE_APPEND
                );
            }

            if ($productData != false) {
                $description = $productData->find('.detail-page-case', 0)->plaintext;
            } else {
                $description = "Не удалось получить описание";
                file_put_contents(
                    "log.txt",
                    "Не удалось получить описание",
                    FILE_APPEND
                );
            }

            echo $link . "</br>";
            //echo $redaction . "</br>";
            //echo $description . "</br>";

            file_put_contents(
                "projects.txt",
                "Номер партнера: $partnerCount\n
                Номер проекта: $projectCount\n
                Адрес сайта проекта: $link\n
                Редакция продукта: $redaction\n
                Описание проекта: $description\n",
                FILE_APPEND
            );


            $projectCount++;
        }

        $partnerCount++;
    }
    sleep(1);
    echo $response;
}

echo $partnerCount;





        /*
        $partnerPage = 'https://www.1c-bitrix.ru/partners/' . trim($a->getAttribute('href'), './');
        $url = $pageData->find('.simple-link', 0)->plaintext;

        file_put_contents(
            "partners.txt",
            "Номер: $count\n
            Имя партнера: $name\n
            Детальная страница на сайте битрикса: $partnerPage\n
            Адрес сайта партнера: $url\n",
            FILE_APPEND
        );
        */

/*
$data = file_get_html('https://www.1c-bitrix.ru/partners/list.php');
foreach ($data->find('.partner-tile-header__title') as $a) {
    echo $a->plaintext . '</br>';
}
*/
// // curl 'https://www.1c-bitrix.ru/partners/index_ajax.php?PAGEN_1=3' \
// //   -H 'Accept: */
//   -H 'Accept - Language: ru - RU,ru;
// q = 0.9,en - US;
// q = 0.8,en;q = 0.7' \
// //   -H 'Bx - ajax: true' \
// //   -H 'Cache - Control: no - cache' \
// //   -H 'Connection: keep - alive' \
// //   -H 'Cookie: LIVECHAT_GUEST_HASH = 450a365831510cf8db17559beeb38d87;
// BITRIX_SM_GUEST_ID = 365587894;
// BITRIX_SM_qmb = 0;
// BX_USER_ID = 1c5cead56277233b91a51a6926f3eb2e;
// _ym_uid = 1723637178490580590;
// _ym_d = 1723637178;
// _ym_isad = 2;
// GEO = RU / TYU;
// PHPSESSID = zW0ONX0tMXrcwkvQmraq4f163XtAgJxd;
// _ym_visorc = w; BITRIX_SM_LAST_VISIT = 15.08.2024 % 2009 % 3A34 % 3A19' \
// //   -H 'Pragma: no - cache' \
// //   -H 'Referer: https://www.1c-bitrix.ru/partners/list.php' \
// //   -H 'Sec-Fetch-Dest: empty' \
// //   -H 'Sec-Fetch-Mode: cors' \
// //   -H 'Sec-Fetch-Site: same-origin' \
// //   -H 'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/127.0.0.0 Safari/537.36' \
// //   -H 'sec-ch-ua: "Chromium";v="127", "Not)A;Brand";v="99"' \
// //   -H 'sec-ch-ua-mobile: ?0' \
// //   -H 'sec-ch-ua-platform: "Linux"'
