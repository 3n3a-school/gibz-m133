<?php

namespace M133;

include_once __DIR__ . '/config.php';

use M133\Importer as Importer;

class ImportPage extends Page {
    public Template $template;
    public Database $database;
    
    function __construct(
        private $importer,
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
            $this->arrayHasKeys( $_POST, ['event'] ) &&
            $this->arrayHasKeys( $_FILES, ['file'] ) &&
            ! empty($_POST['event']) &&
            ! empty($_FILES['file'])
        ) {
            return true;
        }
        return false;
    }

    function handleFormSubmission() {
        $this->importer->readCsvFile(
            $_FILES['file']['tmp_name'],
            $_POST['event']
        );
        $this->importer->saveRankingList();
        $this->config->controllers['rank']->addUserRankings();
        exit();
    }

    public function sendPage() {
        $events = $this->config->controllers['event']->getEventList();
        $event_html = "";

        foreach ($events as $event) {
            $event_html .= "<option value=\"".$event['id']."\">".$event['name']."</option>";
        }

        $this->sendPageWrapper([
            'title' => 'Import',
            'app_content' => 'import.html',
            'event_list' => $event_html
        ]);
    }
}

$importer = new Importer( $config );
$p = new ImportPage(
    $importer,
    $config
);