<?php
require_once('db.php');
require_once('track_visit.php');

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
if (!isset($_GET['id'])) {
    header("Location: incidents.php");
    exit();
}


$inc_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

$sql = "SELECT i.*, iu.user_name 
        FROM incident i 
        JOIN incident_user iu ON i.inc_user_id = iu.inc_user_id 
        WHERE i.inc_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $inc_id);
$stmt->execute();
$incident = $stmt->get_result()->fetch_assoc();

$sql_comments = "SELECT c.*, iu.user_name 
                 FROM comment c 
                 JOIN incident_user iu ON c.inc_user_id = iu.inc_user_id 
                 WHERE c.inc_id = ? ORDER BY c.created_at ASC";
$stmt_comments = $conn->prepare($sql_comments);
$stmt_comments->bind_param("i", $inc_id);
$stmt_comments->execute();
$comments = $stmt_comments->get_result();

$sql_status_history = "SELECT s.*, st.status_type, iu.user_name
                       FROM incident_status s
                       JOIN status_type st ON s.status_type_id = st.status_type_id
                       JOIN incident_user iu ON s.inc_user_id = iu.inc_user_id
                       WHERE s.inc_id = ? ORDER BY s.reported_at ASC";
$stmt_status = $conn->prepare($sql_status_history);
$stmt_status->bind_param("i", $inc_id);
$stmt_status->execute();
$status_history = $stmt_status->get_result();

$sql_images = "SELECT * FROM incident_evidence WHERE inc_id = ?";
$stmt_images = $conn->prepare($sql_images);
$stmt_images->bind_param("i", $inc_id);
$stmt_images->execute();
$images = $stmt_images->get_result();

$status_options = [];
$status_query = $conn->query("SELECT * FROM status_type");
while ($row = $status_query->fetch_assoc()) {
    $status_options[] = $row;
}

$title = "View Incident";
$content = "pages/view_content.php";

include('layout/layout.php');
