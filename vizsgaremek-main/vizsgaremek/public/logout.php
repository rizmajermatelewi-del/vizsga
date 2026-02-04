<?php
session_start();
session_unset();
session_destroy();

// Visszaküldjük a főoldalra
header("Location: index.php");
exit;
?>