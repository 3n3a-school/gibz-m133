<?php

namespace M133;

require_once __DIR__ . '/lib/App.php';
require_once __DIR__ . '/lib/Database.php';
require_once __DIR__ . '/lib/Router.php';
require_once __DIR__ . '/lib/Template.php';

require_once __DIR__ . '/controllers/index.php';

use M133\App as App;
use M133\DatabaseConfig as DbConfig;
use M133\Database as Database;
use M133\ExpressRouter as Router;
use M133\Template as Template;
use M133\Controllers\IndexController as IndexController;

class RankingApp extends App {
    private $controllers = [];

    function __construct(
        private Router $router,
        private Template $template,
        private Database $database,
        private string $views_path = __DIR__ . '/frontend/views/'
    ) {
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

// Create Database Config for Docker Container
$db_config = new DbConfig(
    $_ENV['DB_HOST'],
    $_ENV['DB_PORT'],
    $_ENV['DB_DB'],
    $_ENV['DB_USER'],
    $_ENV['DB_PASS'],
);

// Instantiate new Database Class with Config
$db = new Database($db_config);

// Instantiate new App with Router...
$app = new RankingApp(
    new Router(),
    new Template(),
    $db
);

// Start the App
$app->start();