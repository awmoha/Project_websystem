<?php
require_once 'db.php';
require_once('track_visit.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$userId = $_SESSION['user_id'];  // Se till att användar-ID är korrekt

$stmt = $conn->prepare("
    SELECT iu.user_name, iu.email, r.role_name
    FROM incident_user iu
    JOIN role r ON iu.role_id = r.role_id
    WHERE iu.inc_user_id = ?
");

if ($stmt === false) {
    die('Prepare failed: ' . $conn->error);
}

$stmt->bind_param("i", $userId);
$stmt->execute();

if ($stmt->errno) {
    die('Execute failed: ' . $stmt->error);
}

$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die('User not found.');
}


// Layout-värden
$title = "Profile";
$content = "pages/profile_content.php";

include('layout/layout.php');
?>