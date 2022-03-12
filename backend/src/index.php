<?php

namespace M133;

require_once __DIR__ . '/lib/Router.php';
require_once __DIR__ . '/lib/TemplateY.php';

use M133\Router as Router;
use M133\TemplateY as Tpl;

$views_base = __DIR__ . '/views/';

$router = new Router();

$router->before('GET', '/.*', function () {
    header('X-Powered-By: eServer');
});

$router->get('/.*', function() {
    global $views_base;

    $title = "Ranglisten M133";
    
    $templ = new Tpl($views_base . 'index.html');
    $templ->render([
        'title' => 'Test'
    ]);

});

$router->run();