<?php

namespace M133;

include_once __DIR__ . '/config.php';

use M133\App as App;
use M133\Validate as Validate;

class RankingApp extends App {

    function __construct(
        private Template $template,
        private Database $database,
        private $config
    ) {
        $this->checkInstalled();
        $this->initRoutes();
        $this->sendPage();
    }

    private function checkInstalled() {
        if ( ! file_exists( __DIR__ . '/.setupdone' ) ) {
            // Setup not done
            // Start installation
            header( 'Location: /install.php' );
            error_log("[INSTALLATION] Starting Installation 🤩");
            exit();
        }
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
                    "Profile" => "#show-user-modal",
                    "Settings" => "",
                    "Logout" => "/logout.php"
                ]
            ];
            
            $username = $this->getSessionValueIfExists('username');
            $email = $this->config->controllers['user']->getUser( $username, ["email"] )['email'];
            $this->template->renderIntoBase(
                [
                    'title' => 'Overview',
                    'app_content' => 'index.html',
                    'username' => $username ?? "User",
                    'full_name' => $username ?? "User",
                    'email' => $email,
                ],
                $menus
            );

        } else {
            
            header('Location: /login.php');

        }
    }

    function getSessionValueIfExists( $key ) {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
        return false;
    }

    function isAuthenticated() {
        return isset($_SESSION['is_authenticated']) &&
            $_SESSION['is_authenticated'] === true;
    }
}



// Instantiate new App with Router...
$app = new RankingApp(
    $config->template,
    $config->db,
    $config
);