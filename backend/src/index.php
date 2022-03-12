<?php

require_once __DIR__ . '/lib/Router.php';
require_once __DIR__ . '/lib/TemplateY.php';

$router = new \M133\Router();

$router->before('GET', '/.*', function () {
    header('X-Powered-By: eServer');
});

$router->get('/.*', function() {
    $title = "Ranglisten M133";
    
    $templ = new \M133\TemplateY(__DIR__ . '/views/index.html');
    $templ->render([
        'title' => 'Test'
    ]);

});

$router->run();