<?php
namespace Core;

class Controller {
    protected function render($view, $params = []) {
        extract($params);
        $viewFile = __DIR__ . '/../views/' . $view . '.php';
        include __DIR__ . '/../views/layouts/main.php';
    }
    protected function isPost(){ return $_SERVER['REQUEST_METHOD']==='POST'; }
}
