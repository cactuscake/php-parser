<?php

include 'simple_html_dom.php';

ini_set('max_execution_time', '10000');
set_time_limit(0);
ini_set('memory_limit', '2048M');
ignore_user_abort(true);

echo "<h1>парсер</h1>";

$patern = '/(https).+?(\.php)/';

$text = file_get_contents("partners.txt");

$asd = preg_match_all($patern, $text, $found);

$partnerCount = 1;
$projectCount = 1;

foreach ($found[0] as $a) {
        $pageData = file_get_html($a);
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
            /*
            file_put_contents(
                "log.txt",
                "Не удалось получить страницу",
                FILE_APPEND
            );*/
            sleep(30);
            continue;
        }
        //$productData = file_get_html("https://www.1c-bitrix.ru" . $a->getAttribute('href'));

        if ($productData != false) {
            $link =  $productData->find('.detail-page-list__item-record_value', 3)->plaintext;
        } else {
            $link = "Не удалось получить ссылку";
            /*
            file_put_contents(
                "log.txt",
                "Не удалось получить ссылку",
                FILE_APPEND
            );*/
        }

        if ($productData != false) {
            $redaction =  $productData->find('.detail-page-list__item-record_value', 2)->plaintext;
        } else {
            $redaction = "Не удалось получить редакцию";
            /*
            file_put_contents(
                "log.txt",
                "Не удалось получить редакцию",
                FILE_APPEND
            );*/
        }

        if ($productData != false) {
            $description = $productData->find('.detail-page-case', 0)->plaintext;
        } else {
            $description = "Не удалось получить описание";
            /*
            file_put_contents(
                "log.txt",
                "Не удалось получить описание",
                FILE_APPEND
            );*/
        }

        echo $link . "</br>";
        echo $redaction . "</br>";
        echo $description . "</br>";
        /*
        file_put_contents(
            "projects.txt",
            "Номер партнера: $partnerCount\n
            Номер проекта: $projectCount\n
            Адрес сайта проекта: $link\n
            Редакция продукта: $redaction\n
            Описание проекта: $description\n",
            FILE_APPEND
        );*/


        $projectCount++;
    }

        $partnerCount++;

    sleep(1);
    echo $response;
}
