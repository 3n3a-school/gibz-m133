<?php

namespace M133;

include_once __DIR__ . '/config.php';

use M133\Page as Page;

class CalendarPage extends Page {

    public function sendPage() {
        $events = $this->config->controllers['event']->getAllEvents( true );
        $events_html = "";

        if ( ! empty($events) ) {
            foreach ($events as $event) {
                $name = $event["name"];
                $id = $event["id"];
                $events_html .= $this->template->render('components/event_item.html', ["id"=>$id,"name"=>$name, 'url_prefix' => "details.php?event_id=",], true);
            } 
        } else {
            $events_html = "<p>No Calendar entries found</p>";
        }
        
        $this->sendPageWrapper([
            'title' => 'Calendar',
            'app_content' => 'events.html',
            'events' => $events_html
        ]);
    }
}

// Instantiate new App with Router...
$index = new CalendarPage(
    $config
);