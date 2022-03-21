<?php

include_once __DIR__ . '/config.php';

// Destroy current session 
session_start();
session_unset();
session_destroy();
header('Location: /');
error_log("[LOGOUT]");
exit();