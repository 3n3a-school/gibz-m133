<?php

namespace M133;

include_once __DIR__ . '/config.php';

use M133\Importer as Importer;

class AddEventPage extends Page {
    public Template $template;
    public Database $database;
    
    function __construct(
        public $config
    ) {
        $this->template = $this->config->template;
        $this->database = $this->config->db;

        $this->checkInstalled($this->config->setupDonePath);
        $this->initRoutes();

        if ( ! $this->hasAdmin() ) {
            header( 'Location: /index.php' );
            exit();
        }
        
        if ($this->checkIsSubmission()) {
            $this->handleFormSubmission();
        }

        $this->sendPage();
    }

    function checkIsSubmission() {
        if (
            $this->arrayHasKeys( $_POST, ['name', 'organizer_id', 'date', 'place'] ) &&
            ! empty($_POST['name']) &&
            ! empty($_POST['organizer_id']) &&
            ! empty($_POST['date']) &&
            ! empty($_POST['place'])
        ) {
            return true;
        }
        return false;
    }

    function handleFormSubmission() {
        $this->config->controllers['event']->addEvent(
            $_POST
        );
        echo "Event added successfully";
        exit();
    }

    public function sendPage() {
        $clubs = $this->config->controllers['club']->getAllClubs();
        $clubs_html = "";
        foreach ($clubs as $club) {
            $clubs_html .= "<option value=\"".$club['id']."\">".$club['name']."</option>";
        }

        $this->sendPageWrapper([
            'title' => 'Create Event',
            'app_content' => 'new_event.html',
            'club_list' => $clubs_html
        ]);
    }
}

$p = new AddEventPage(
    $config
);