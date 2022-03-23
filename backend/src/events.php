<?php

namespace M133;

include_once __DIR__ . '/config.php';

use M133\Page as Page;

class EventPage extends Page {

    public function sendPage() {
        $events = $this->config->controllers['event']->getAllEvents();
        $events_html = "";

        foreach ($events as $event) {
            $name = $event["name"];
            $id = "categories.php?event_id=" . $event["id"];
            $events_html .= $this->template->render('components/event_item.html', ["id"=>$id,"name"=>$name], true);
        }
        
        $this->sendPageWrapper([
            'title' => 'Events',
            'app_content' => 'events.html',
            'events' => $events_html
        ]);
    }
}

// Instantiate new App with Router...
$index = new EventPage(
    $config
);