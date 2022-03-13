<?php

namespace M133;

require_once __DIR__ . '/lib/App.php';
require_once __DIR__ . '/lib/Database.php';
require_once __DIR__ . '/lib/Router.php';
require_once __DIR__ . '/lib/Template.php';

require_once __DIR__ . '/controllers/index.php';
require_once __DIR__ . '/controllers/IndexController.php';

use M133\App as App;
use M133\Database as Database;
use M133\ExpressRouter as Router;
use M133\template as Template;
use M133\IndexController as IndexController;

class RankingApp extends App {
    private $router = NULL;
    private $template = NULL;
    private $controllers = [];
    private $database = NULL;

    private $views_path = __DIR__ . '/frontend/views/';

    function __construct() {
        $this->router = new Router();
        $this->template = new Template();
        $this->database = new Database();
        $this->controllers['index'] = new IndexController($this->database);

        $this->initRoutes();
    }

    public function initRoutes() {
        $this->router->registerMiddleware(['GET'], '/.*', function () {
            header('X-Powered-By: eServer');
        });
        
        $this->router->get('/{path}', function($path) {
            $this->template->render($this->views_path . 'index.html', [
                'title' => $this->controllers['index']->handleGet(),
                'footer' => $this->views_path . 'footer.html',
                'address' => '123 street 4'
            ]);
        });
    }

    public function start() {
        $this->router->start();
    }
}

$app = new RankingApp();
$app->start();