<?php
session_start();

// clear session
$_SESSION = [];
session_destroy();

// correct redirect
header("Location: /resourcetracker/login/index.php");
exit;
?>