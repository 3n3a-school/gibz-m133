<?php

namespace M133;

session_start();

include_once __DIR__ . '/../config.php';

if (isset($_GET['rank_id']) && 
    isset($_GET['redirect_uri']) &&
    isset($_GET['hide']) &&
    is_numeric($_GET['rank_id'])
) {
    $config->controllers['rank']->changeUserRanking( 
        $_GET['rank_id'], 
        $_SESSION['username'], 
        $_GET['hide'] 
    );

    header('Location: /'.$_GET['redirect_uri']);
    exit();
} else {
    echo "No input provided";
}