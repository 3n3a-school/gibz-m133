<?php

namespace M133;

include_once __DIR__ . '/config.php';
include_once __DIR__ . '/controllers/index.php';

use M133\App as App;
use M133\Validate as Validate;
use M133\Controllers\IndexController as IndexController;

class RankingApp extends App {
    private $controllers = [];

    function __construct(
        private Template $template,
        private Database $database,
    ) {
        $this->controllers['index'] = new IndexController($this->database);

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
            $mobile_personmenu = "";
            $desktop_personmenu = "";

            foreach ([
                "Profile" => "",
                "Settings" => "",
                "Logout" => ""
            ] as $title => $url) {
                $mobile_personmenu .= $this->template->render('components/mobile_menu_item.html', [
                    'title' => $title,
                    'url' => $url
                ], true);
                
                $desktop_personmenu .= $this->template->render('components/desktop_menu_item.html', [
                    'title' => $title,
                    'url' => $url
                ], true);
            }
            
            $this->template->render('base.html', [
                'head_scripts' => 'head_scripts.html',
                'title' => $this->controllers['index']->handleGet(),
                'content' => 'base_app.html',
                'mobile_personmenu' => $mobile_personmenu,
                'desktop_personmenu' => $desktop_personmenu,
                'app_content' => 'index.html',
                'user_fullname' => 'Enea',
                'user_email' => 'email@email.com',
                'footer' => 'footer.html',
                'address' => '123 street 4'
            ]);

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