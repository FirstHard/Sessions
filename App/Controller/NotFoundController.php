<?php

namespace App\Controller;

class NotFoundController
{
    public function actionIndex(array $data = [])
    {
        extract($data);
        header("Status: " . $code . " " . $status);
        header("HTTP/1.1 " . $code . " " . $status);
        // Далее - подключить шаблон и вывести в нем информацию вроде этой:
        echo 'Извините, но запрошенная Вами страница "' . $escaped_url . '" не найдена на нашем сайте по причине: ' . $message . '.';
    }
}
