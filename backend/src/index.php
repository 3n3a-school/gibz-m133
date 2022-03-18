<?php

namespace M133;

include_once __DIR__ . '/config.php';
include_once __DIR__ . '/controllers/index.php';

use M133\App as App;
use M133\Validate as Validate;
use M133\Controllers\UserController as UserController;

class RankingApp extends App {
    private $controllers = [];

    function __construct(
        private Template $template,
        private Database $database,
    ) {
        $this->controllers['user'] = new UserController($this->database);

        $this->checkInstalled();
        $this->initRoutes();
        $this->sendPage();
    }

    private function checkInstalled() {
        if ( ! file_exists( __DIR__ . '/.setupdone' ) ) {
            // Setup not done
            // Start installation
            header( 'Location: /install.php' );
            exit();
        }
        error_log("Already installed");
    }

    public function initRoutes() {
        // Authentication
        session_start();
        
        header('X-Powered-By: eServer');
    }

    public function sendPage() {
        if ($this->isAuthenticated()) {
            $menus = [
                'main' => [
                    "Home" => "/index.php",
                    "Ranglisten" => "",
                    "Kategorien"=> "",
                    "Wettkämpfe" => ""
                ],
                'person' => [
                    "Profile" => "",
                    "Settings" => "",
                    "Logout" => "/logout.php"
                ]
            ];
            
            // TODO: put in template class
            $this->template->renderIntoBase(
                [
                    'title' => 'Overview',
                    'app_content' => 'index.html',
                    'user_fullname' => 'Enea',
                    'user_email' => 'email@email.com',
                ],
                $menus
            );

        } else {
            
            header('Location: /login.php');

        }
    }

    function isAuthenticated() {
        return isset($_SESSION['is_authenticated']) &&
            $_SESSION['is_authenticated'] === true;
    }
}



// Instantiate new App with Router...
$app = new RankingApp(
    $config->template,
    $config->db
);