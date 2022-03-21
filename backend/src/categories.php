<?php

namespace M133;

include_once __DIR__ . '/config.php';

use M133\Page as Page;

class CategoryPage extends Page {

    private $current_event_id;

    function __construct(
        private Template $template,
        private Database $database,
        private $config
    ) {
        $this->checkInstalled(__DIR__ . '/.setupdone');
        $this->initRoutes();
        $this->getKeys();
        $this->sendPage();
    }

    function getKeys() {
        if (array_key_exists('event_id', $_GET)) {
            $this->current_event_id = $_GET['event_id'];
        } else {
            header('Location: /events.php');
        }
    }

    public function sendPage() {
        if ($this->isAuthenticated()) {

            $this->current_event_id

            // $categories = $this->config->controllers['category']->getAllEvents();
            // $categories_html = "";

            // foreach ($events as $event) {
            //     $name = $event["name"];
            //     $id = "categories.php?event_id=" . $event["id"];
            //     $events_html .= $this->template->render('components/event_item.html', ["id"=>$id,"name"=>$name], true);
            // }
            
            $username = $this->getSessionValueIfExists('username');
            $email = $this->config->controllers['user']->getUser( $username, ["email"] )['email'];
            $this->template->renderIntoBase(
                [
                    'title' => 'Categories ' . $event['name'],
                    'app_content' => 'categories.html',
                    'username' => $username ?? "User",
                    'full_name' => $username ?? "User",
                    'email' => $email,
                    'categories' => $categories_html
                ],
                $this->config->menus
            );

        } else {
            
            header('Location: /login.php');

        }
    }
}



// Instantiate new App with Router...
$index = new CategoryPage(
    $config->template,
    $config->db,
    $config
);