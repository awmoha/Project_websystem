<?php 
require_once('db.php');
require_once('track_visit.php');

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$title = "SLA - Incident Status";

$content = "pages/sla_content.php";

$user_role = $_SESSION['role_name']; // Ex: 'Administrator', 'Responder'

include('layout/layout.php');
?>
