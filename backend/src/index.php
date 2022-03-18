<?php

namespace M133;

include_once __DIR__ . '/config.php';
include_once __DIR__ . '/controllers/index.php';

use M133\App as App;
use M133\Validate as Validate;
use M133\Controllers\IndexController as IndexController;

class RankingApp extends App {
    private $controllers = [];
    private $current_page;

    function __construct(
        private Template $template,
        private Database $database,
    ) {
        $this->controllers['index'] = new IndexController($this->database);
        $this->current_page = $_SERVER["SCRIPT_NAME"];

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

    private function renderPersonMenu( $menu_arr ) {
        $mobile_personmenu = "";
        $desktop_personmenu = "";

        foreach ($menu_arr as $title => $url) {
            $mobile_personmenu .= $this->renderMenuItem( $title, $url, 'components/mobile_personmenu_item.html');
            $desktop_personmenu .= $this->renderMenuItem( $title, $url, 'components/desktop_personmenu_item.html');
        }

        return [$mobile_personmenu, $desktop_personmenu];
    }

    private function renderMainMenu( $menu_arr ) {
        $desktop_menu = "";
        $mobile_menu = "";

        foreach ($menu_arr as $title => $url) {
            if ( $this->isActivePage($url) ) {
                $desktop_menu .= $this->renderMenuItem( $title, $url, 'components/desktop_menu_item_active.html');
                $mobile_menu .= $this->renderMenuItem( $title, $url, 'components/mobile_menu_item_active.html');
            } else {
                $desktop_menu .= $this->renderMenuItem( $title, $url, 'components/desktop_menu_item.html');
                $mobile_menu .= $this->renderMenuItem( $title, $url, 'components/mobile_menu_item.html');
            }
        }

        return [$desktop_menu, $mobile_menu];
    }

    private function isActivePage( $url ) {
        return $url == $this->current_page;
    }

    private function renderMenuItem( $title, $url, $template ) {
        return $this->template->render($template, [
            'title' => $title,
            'url' => $url
        ], true);
    }

    public function sendPage() {
        if ($this->isAuthenticated()) {
            [$mobile_personmenu, $desktop_personmenu] = $this->renderPersonMenu(
                [
                    "Profile" => "",
                    "Settings" => "",
                    "Logout" => ""
                ]
            );

            [$desktop_menu, $mobile_menu] = $this->renderMainMenu( [
                "Home" => "/index.php",
                "Ranglisten" => "",
                "Kategorien"=> "",
                "Wettkämpfe" => ""
            ]);
            
            // TODO: put in template class
            $this->template->render('base.html', [
                'head_scripts' => 'head_scripts.html',
                'title' => $this->controllers['index']->handleGet(),
                'content' => 'base_app.html',
                'mobile_personmenu' => $mobile_personmenu,
                'desktop_personmenu' => $desktop_personmenu,
                'desktop_menu' => $desktop_menu,
                'mobile_menu' => $mobile_menu,
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