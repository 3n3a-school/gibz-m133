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

        $this->checkCorrectRoles();
        
        if ($this->checkIsSubmission()) {
            $this->handleFormSubmission();
        }

        $this->sendPage();
    }

    /**
     * Verifies that User has acess rights to import
     * otherwise redirects to home
     */
    function checkCorrectRoles() {
        $username = $this->getSessionValueIfExists('username');
        $user_roles_raw = $this->config->controllers['user']->getUserRoles(
            $username
        );
        $user_roles = [];

        if ( ! empty($user_roles_raw) ) {
            foreach ($user_roles_raw as $role) {
                array_push( $user_roles, $role['role_name']);
            }
        } else {
            array_push($user_roles, 'user');
        }


        if (
            ! in_array( 'admin', $user_roles ) ||
            ! in_array( 'owner', $user_roles )
        ) {
            header( 'Location: /index.php' );
            error_log("[IMPORT] Unauthorized Import " . $username);
            exit();
        }
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