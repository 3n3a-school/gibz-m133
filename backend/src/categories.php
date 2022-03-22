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

            $categories = $this->config->controllers['category']->getAllCategories();
            $categories_html = "";

            foreach ($categories as $cat) {
                $name = $cat["name"];
                $id = "rankings.php?event_id=" . $this->current_event_id . "&category_id=" . $cat['id'];
                $categories_html .= $this->template->render('components/event_item.html', ["id"=>$id,"name"=>$name], true);
            }
            
            $username = $this->getSessionValueIfExists('username');
            $email = $this->config->controllers['user']->getUser( $username, ["email"] )['email'];
            $event_name = $this->config->controllers['event']->getEventName($this->current_event_id) ?? "No Event Name found";
            $this->template->renderIntoBase(
                [
                    'title' => 'Categories: ' . $event_name,
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