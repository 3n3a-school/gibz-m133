<?php

namespace M133;

include_once __DIR__ . '/config.php';

use M133\Page as Page;

class DetailsPage extends Page {

    private $current_event_id;

    function __construct(
        public $config
    ) {
        $this->template = $this->config->template;
        $this->database = $this->config->db;

        $this->checkInstalled($this->config->setupDonePath);
        $this->initRoutes();
        $this->getKeys();
        $this->sendPage();
    }

    function getKeys() {
        if (array_key_exists('event_id', $_GET) &&
        $_GET['event_id'] != null) {
            $this->current_event_id = $_GET['event_id'];
        } else {
            header('Location: /events.php');
            exit();
        }
    }

    public function sendPage() {
        $event_name = $this->config->controllers['event']->getEventName( $this->current_event_id );
        
        $event_meta = $this->config->controllers['event']->getEventMeta( $this->current_event_id );
        $event_meta_html = "";

        foreach ($event_meta as $em) {
            $event_meta_html .= $this->template->render('components/event_meta.html', [
                "name" => $em['name'],
                "description" => $em['description']
            ], true);
        }
        $this->sendPageWrapper([
            'title' => $event_name,
            'app_content' => 'event_details.html',
            'event_meta' => $event_meta_html
        ]);
    }
}

// Instantiate new App with Router...
$index = new DetailsPage(
    $config
);