<?php

namespace M133;

include_once __DIR__ . '/lib/index.php';
include_once __DIR__ . '/controllers/index.php';
include_once __DIR__ . '/config.php';

use M133\App as App;
use M133\DatabaseConfig as DbConfig;
use M133\Database as Database;
use M133\Template as Template;
use M133\Controllers\IndexController as IndexController;

class RankingApp extends App {
    private $controllers = [];

    function __construct(
        private Template $template,
        private Database $database,
    ) {
        $this->controllers['index'] = new IndexController($this->database);

        $this->initRoutes();
        $this->sendPage();
    }

    public function initRoutes() {
        // Authentication
        session_start();
        
        header('X-Powered-By: eServer');
    }


    public function sendPage() {
        if (isset($_SESSION['is_authenticated']) &&
            $_SESSION['is_authenticated'] === true) {

            $this->template->render('index.html', [
                'title' => $this->controllers['index']->handleGet(),
                'footer' => 'footer.html',
                'address' => '123 street 4'
            ]);

        } else {
            
            header('Location: /login.php');

        }
    }
}



// Instantiate new App with Router...
$app = new RankingApp(
    $config->template,
    $config->db
);