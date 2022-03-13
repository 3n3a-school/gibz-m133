<?php

namespace M133;

require_once __DIR__ . '/lib/Router.php';
require_once __DIR__ . '/lib/Template.php';

use M133\ExpressRouter as Router;
use M133\Template as Tpl;

$views_base = __DIR__ . '/frontend/views/';

$r = new Router();
$templ = new Tpl();

$r->registerMiddleware(['GET'], '/.*', function () {
    header('X-Powered-By: eServer');
});

$r->get('/{path}', function($path) {
    global $views_base, $templ;

    $title = "Ranglisten M133";
    $templ->render($views_base . 'index.html', [
        'title' => $path ?? "TEst",
        'footer' => $views_base . 'footer.html',
        'address' => '123 street 4'
    ]);
});

$r->start();
