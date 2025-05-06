<?php
session_start();
require_once 'db.php';
require_once('track_visit.php');


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$title = "Dashboard";
$content = "pages/index_content.php";
include("layout/layout.php");
