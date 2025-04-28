<?php
require_once('db.php');
session_start();

if (!isset($_POST['inc_id']) || empty($_FILES['image'])) {
    header("Location: incidents.php");
    exit();
}

$inc_id = intval($_POST['inc_id']);
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];
$uploadDir = 'uploads/'; // Skapa denna mapp om den inte finns
$allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

$sql = "SELECT inc_user_id FROM incident WHERE inc_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $inc_id);
$stmt->execute();
$incident = $stmt->get_result()->fetch_assoc();

if ($role == 'Reporter' && $incident['inc_user_id'] !== $user_id) {
    $_SESSION['flash_message'] = "You are not authorized to upload images to this incident.";
    header("Location: view.php?id=$inc_id");
    exit();
}

foreach ($_FILES['image']['tmp_name'] as $key => $tmpName) {
    $fileName = basename($_FILES['image']['name'][$key]);
    $fileType = $_FILES['image']['type'][$key];
    $fileTmp = $_FILES['image']['tmp_name'][$key];
    $fileError = $_FILES['image']['error'][$key];
    
    if ($fileError === UPLOAD_ERR_OK && in_array($fileType, $allowedTypes)) {
        $targetFilePath = $uploadDir . time() . "_" . $fileName;
        
        if (move_uploaded_file($fileTmp, $targetFilePath)) {
            $sql = "INSERT INTO incident_evidence (inc_id, inc_user_id, file_path, uploaded_at)
                    VALUES (?, ?, ?, NOW())";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iis", $inc_id, $user_id, $targetFilePath);
            $stmt->execute();
        }
    }
}

$_SESSION['flash_message'] = "Images uploaded successfully.";
header("Location: view.php?id=$inc_id");
exit();
?>
