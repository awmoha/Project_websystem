<?php
require_once('db.php');
session_start();

if (!isset($_POST['inc_id']) || !isset($_POST['description'])) {
    header("Location: incidents.php");
    exit();
}

$inc_id = intval($_POST['inc_id']);
$description = trim($_POST['description']);
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

if ($role !== 'Administrator' && $role !== 'Reporter') {
    $_SESSION['flash_message'] = "You are not authorized to update the description.";
    header("Location: view.php?id=$inc_id");
    exit();
}

$sql = "UPDATE incident SET description = ? WHERE inc_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $description, $inc_id);
$stmt->execute();

$_SESSION['flash_message'] = "Description updated successfully.";
header("Location: view.php?id=$inc_id");
exit();
?>
