<?php

namespace M133;

include_once __DIR__ . '/config.php';

use M133\Page as Page;

class EventPage extends Page {

    function __construct(
        private Template $template,
        private Database $database,
        private $config
    ) {
        $this->checkInstalled(__DIR__ . '/.setupdone');
        $this->initRoutes();
        $this->sendPage();
    }

    public function sendPage() {
        if ($this->isAuthenticated()) {

            $events = $this->config->controllers['event']->getAllEvents();
            $events_html = "";

            foreach ($events as $event) {
                $name = $event["name"];
                $id = "categories.php?event_id=" . $event["id"];
                $events_html .= $this->template->render('components/event_item.html', ["id"=>$id,"name"=>$name], true);
            }
            
            $username = $this->getSessionValueIfExists('username');
            $email = $this->config->controllers['user']->getUser( $username, ["email"] )['email'];
            $this->template->renderIntoBase(
                [
                    'title' => 'Events',
                    'app_content' => 'events.html',
                    'username' => $username ?? "User",
                    'full_name' => $username ?? "User",
                    'email' => $email,
                    'events' => $events_html
                ],
                $this->config->menus
            );

        } else {
            
            header('Location: /login.php');

        }
    }
}



// Instantiate new App with Router...
$index = new EventPage(
    $config->template,
    $config->db,
    $config
);