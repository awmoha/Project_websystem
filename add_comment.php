<?php
require_once('db.php');
session_start();

if (!isset($_POST['inc_id']) || !isset($_POST['content'])) {
    header("Location: incidents.php");
    exit();
}

$inc_id = intval($_POST['inc_id']);
$content = trim($_POST['content']);
$user_id = $_SESSION['user_id'];

if ($content != '') {
    $sql = "INSERT INTO comment (inc_user_id, inc_id, content, created_at)
            VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $user_id, $inc_id, $content);
    $stmt->execute();
}

header("Location: view.php?id=$inc_id");
exit();
?>
