<?php

namespace M133;

include_once __DIR__ . '/config.php';

use M133\Page as Page;

class RankingPage extends Page {

    private $current_event_id;
    private $current_cat_id;

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
        if (array_key_exists('event_id', $_GET) &&
        array_key_exists('category_id', $_GET)) {
            $this->current_event_id = $_GET['event_id'];
            $this->current_category_id = $_GET['category_id'];
        } else {
            header('Location: /events.php');
        }
    }

    public function sendPage() {
        if ($this->isAuthenticated()) {

            $ranking = $this->config->controllers['rank']->getRanking( $this->current_event_id, $this->current_category_id );
            $ranking_entries = "";

            foreach ($ranking as $r) {
                $ranking_entries .= $this->template->render('components/ranking_table_entry.html', [
                    "position"=>$r['position'],
                    "full_name"=>$r['participant_name'],
                    "birthyear"=>$r['birthyear'],
                    "city"=>$r['city'],
                    "club"=>$r['club'],
                    "time"=>$r['time'],
                ], true);
            }
            
            $username = $this->getSessionValueIfExists('username');
            $email = $this->config->controllers['user']->getUser( $username, ["email"] )['email'];
            $event_name = $this->config->controllers['event']->getEventName($this->current_event_id) ?? "No Event Name found";
            $cat_name = $this->config->controllers['category']->getCatName($this->current_category_id) ?? "No Cat Name found";
            $this->template->renderIntoBase(
                [
                    'title' => 'Ranking: ' . $event_name . ': ' . $cat_name,
                    'app_content' => 'rankings.html',
                    'username' => $username ?? "User",
                    'full_name' => $username ?? "User",
                    'email' => $email,
                    'ranking_table' => 'components/ranking_table.html',
                    'entries' => $ranking_entries
                ],
                $this->config->menus
            );

        } else {
            
            header('Location: /login.php');

        }
    }
}



// Instantiate new App with Router...
$index = new RankingPage(
    $config->template,
    $config->db,
    $config
);