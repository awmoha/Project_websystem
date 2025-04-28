<?php
require_once('db.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment_id = intval($_POST['comment_id']);
    $inc_id = intval($_POST['inc_id']);
    $content = trim($_POST['content']);
    $user_id = $_SESSION['user_id'];
    $role = $_SESSION['role'];

    $sql = "SELECT * FROM comment WHERE comment_id = ? AND inc_id = ? AND (inc_user_id = ? OR ? = 'Administrator')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiis", $comment_id, $inc_id, $user_id, $role);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $update_sql = "UPDATE comment SET content = ? WHERE comment_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("si", $content, $comment_id);
        $update_stmt->execute();

        $_SESSION['flash_message'] = "Comment updated successfully!";
        header("Location: view.php?id=" . $inc_id);
        exit();
    } else {
        echo "You are not authorized to edit this comment.";
        exit();
    }
}
