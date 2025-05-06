<?php
session_start();
require_once('track_visit.php');
session_unset();
session_destroy();
header("Location: index.php");
exit();
?>
