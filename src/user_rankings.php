<?php

namespace M133;

include_once __DIR__ . '/config.php';

use M133\Page as Page;

class UserRankingPage extends Page {

    public function sendPage() {
        $ranking = $this->config->controllers['rank']->getUserRankings( 
            $this->getSessionValueIfExists('username') 
        );
        $ranking_entries = "";

        if ( ! empty($ranking) ) {
            foreach ($ranking as $r) {
                $hidden_value = $r['ur_hidden'] == "1" ? "0" : "1";
                $ranking_entries .= $this->template->render('components/user_ranking_table_entry.html', [
                    "event_name"=>$r['event_name'],
                    "position"=>$r['position'],
                    "category"=>$r['category_name'],
                    "time"=>$r['time'],
                    "ranking_url"=> "rankings.php?event_id=".$r['event_id']."&category_id=".$r['category_id'],
                    'hide_url' => "api/user_ranking.php?rank_id=".$r['ranking_id']."&redirect_uri=".basename($_SERVER['PHP_SELF'])."&hide=".$hidden_value,
                    'hide_text' => $r['ur_hidden'] == "1" ? "Unhide" : "Hide"
                ], true);
            }
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