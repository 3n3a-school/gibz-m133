<?php

namespace M133;

include_once __DIR__ . '/config.php';

use M133\Page as Page;

class UserRankingPage extends Page {

    public function sendPage() {
        $ranking = $this->config->controllers['rank']->getUserRankings( $this->getSessionValueIfExists('username') );
        $ranking_entries = "";

        foreach ($ranking as $r) {
            $ranking_entries .= $this->template->render('components/user_ranking_table_entry.html', [
                "event_name"=>$r['event_name'],
                "position"=>$r['position'],
                "category"=>$r['category_name'],
                "time"=>$r['time'],
                "ranking_url"=> "rankings.php?event_id=".$r['event_id']."&category_id=".$r['category_id'],
            ], true);
        }
        
        $this->sendPageWrapper(
            [
                'app_content' => 'rankings.html',
                'title' => 'Your Rankings',
                'ranking_table' => 'components/user_ranking_table.html',
                'entries' => $ranking_entries,
            ]
        );
    }
}

// Instantiate new App with Router...
$index = new UserRankingPage(
    $config
);