<?php
require_once('db.php');
session_start();

if (!isset($_SESSION['user_id']) || !isset($_GET['comment_id']) || !isset($_GET['inc_id'])) {
    header("Location: incidents.php");
    exit();
}
$user_id = $_SESSION['user_id'];
$comment_id = intval($_GET['comment_id']);
$inc_id = intval($_GET['inc_id']);

$sql = "SELECT inc_user_id FROM comment WHERE comment_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $comment_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: view_incident.php?id=$inc_id");
    exit();
}

$comment = $result->fetch_assoc();

if ($comment['inc_user_id'] == $user_id || $_SESSION['role'] == 'Administrator') {
    $delete_sql = "DELETE FROM comment WHERE comment_id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("i", $comment_id);
    $delete_stmt->execute();

    $_SESSION['flash_message'] = "Comment deleted successfully.";
} else {
    $_SESSION['flash_message'] = "You do not have permission to delete this comment.";
}

header("Location: view.php?id=$inc_id");
exit();
?>
