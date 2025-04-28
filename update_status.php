<?php
require_once('db.php');
session_start();

if (!isset($_POST['inc_id']) || !isset($_POST['status_type_id'])) {
    header("Location: incidents.php");
    exit();
}

$inc_id = intval($_POST['inc_id']);
$status_type_id = intval($_POST['status_type_id']);
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Incident responders och Administrators kan ändra status på alla incidenter
if ($role !== 'Administrator' && $role !== 'Responder') {
    $_SESSION['flash_message'] = "You are not authorized to update the status.";
    header("Location: view.php?id=$inc_id");
    exit();
}

// Lägg till en rad i incident_status tabellen
$sql = "INSERT INTO incident_status (inc_id, status_type_id, inc_user_id, reported_at)
        VALUES (?, ?, ?, NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $inc_id, $status_type_id, $user_id);
$stmt->execute();

$_SESSION['flash_message'] = "Status updated successfully.";
header("Location: view.php?id=$inc_id");
exit();
?>
