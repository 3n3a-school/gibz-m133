<?php

namespace M133;

include_once __DIR__ . '/config.php';

use M133\Page as Page;

class EventPage extends Page {

    public function sendPage() {
        $events = $this->config->controllers['event']->getAllEvents();
        $events_html = "";

        if ( ! empty($events) ) {
            foreach ($events as $event) {
                $name = $event["name"];
                $id = $event["id"];
                $events_html .= $this->template->render('components/event_item.html', [
                    "id"=>$id,
                    "name"=>$name, 
                    'url_prefix' => "categories.php?event_id=",
                    "date_d" => date('d', strtotime($event['date'])),
                    "date_rest" => date('F, Y', strtotime($event['date']))
                ], true);
            }
        } else {
            $events_html = "<p>No Events found</p>";
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