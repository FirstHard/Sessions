<?php

namespace App\Core;

use App\Controller\NotFoundController;

class Route
{
    public function start()
    {
        $parsed_url = parse_url($_SERVER['REQUEST_URI']);
        $query = null;
        $query_data = [];

        if (!empty($query = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY))) {
            $query_data = QueryParser::buildData($query);
        }

        /* echo '<pre>';
        print_r($query_data);
        echo '</pre>'; */

        // контроллер и действие по умолчанию
        $controller_name = 'Site';
        $action = 'index';
        $param = false;
        // разбивка строки запроса по роутам
        $routes = explode('/', $parsed_url['path']);
        // получаем имя контроллера
        if (!empty($routes[1])) {
            $controller_name = $routes[1];
        }
        // получаем имя экшена
        if (!empty($routes[2])) {
            $action = $routes[2];
        }
        // получаем параметры
        if (!empty($routes[3])) {
            $param = $routes[3];
        }
        $controller_name = $controller_name . 'Controller';
        $controller_class = "App\\Controller\\" . $controller_name;
        $action = 'action' . ucfirst($action);
        $requested_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $data['escaped_url'] = htmlspecialchars($requested_url, ENT_QUOTES, 'UTF-8');
        if (!class_exists($controller_class)) {
            // Не найден контроллер!
            $data['message'] = 'Не найден контроллер';
            $data['code'] = 404;
            $data['status'] = 'Not Found';
            $controller = new NotFoundController();
            $controller->actionIndex($data);
        } else {
            $controller = new $controller_class();
            if (!method_exists($controller, $action)) {
                // Не найден метод!
                $data['message'] = 'Не найден метод';
                $data['code'] = 404;
                $data['status'] = 'Not Found';
                $controller = new NotFoundController();
                $controller->actionIndex($data);
            }
            $controller = $controller->$action($param, $query_data);
        }
    }
}
