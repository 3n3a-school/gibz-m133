<?php

namespace M133;

include_once __DIR__ . '/lib/App.php';
include_once __DIR__ . '/lib/Database.php';
include_once __DIR__ . '/lib/Router.php';
include_once __DIR__ . '/lib/Template.php';

include_once __DIR__ . '/lib/Controller.php';
include_once __DIR__ . '/controllers/index.php';

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
    ) {
        $this->controllers['index'] = new IndexController($this->database);

        $this->initRoutes();
    }

    public function initRoutes() {
        $this->router->registerMiddleware(['GET', 'POST'], '/.*', function () {
            header('X-Powered-By: eServer');

            // Authentication
            session_start();
            $_SESSION['is_authenticated'] = false;
            $_SESSION['session_timeout'] = NULL;
            $_SESSION['username'] = NULL;
        });

        $this->indexRoute();
        $this->loginRoute();
    }
    
    private function indexRoute() {
        $this->router->get('/', function() {

            if ($_SESSION['is_authenticated']) {

                $this->template->render('index.html', [
                    'title' => '',//$this->controllers['index']->handleGet(),
                    'footer' => 'footer.html',
                    'address' => '123 street 4'
                ]);

            } else {

                $this->template->render(
                    'login.html', [
                        'auth' => $_SESSION['is_authenticated'] ? 'true' : 'false'
                    ]
                );

            }
        });
    }

    private function loginRoute() {
        $this->router->post('/login', function() {

            if ( !$_SESSION['is_authenticated'] &&
                array_key_exists('username', $_POST) &&
                array_key_exists('password', $_POST)
            ) {

                $username = $_POST['username'];
                $password = $_POST['password'];

                if ( $username == "admin" && $password == "123" ) {
                    $_SESSION['is_authenticated'] = true;
                    $_SESSION['session_timeout'] = strtotime("+1 day");
                }
                
            }

            header("Location: /");
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
$views_path = __DIR__ . '/frontend/views/';
$app = new RankingApp(
    new Router(),
    new Template($views_path),
    $db
);

// Start the App
$app->start();