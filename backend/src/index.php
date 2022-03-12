<?php

namespace M133;

require_once __DIR__ . '/lib/Router.php';
require_once __DIR__ . '/lib/TemplateY.php';

use M133\Router as Router;
use M133\TemplateY as Tpl;

$views_base = __DIR__ . '/frontend/views/';

$router = new Router();
$templ = new Tpl();

$router->middleware('GET', '/.*', function () {
    header('X-Powered-By: eServer');
});

$router->get('/.*', function() {
    global $views_base, $templ;

    $title = "Ranglisten M133";
    $templ->render($views_base . 'index.html', [
        'title' => 'Test',
        'footer' => $views_base . 'footer.html',
        'address' => '123 street 4'
    ]);
});

$router->run();