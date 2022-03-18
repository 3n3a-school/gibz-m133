<?php

// Destroy current session 
session_start();
session_unset();
session_destroy();
header('Location: /');
exit();