<?php
require_once('db.php');
require_once('track_visit.php');

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}


$title = "View Incident";
$content = "pages/view_content.php";

include('layout/layout.php');
?>
